<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Discipline;
use App\Models\Genre;
use App\Models\IntakeRequest;
use App\Models\RecommendationSet;
use App\Models\RecommendationSetItem;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class RecommendationController extends Controller
{
    /** 추천안 상세(아티스트 담기/편집/공유/필터) */
    public function show(IntakeRequest $intake)
    {
        // 1) 세트 목록만 먼저 로드
        $intake->loadMissing('recommendationSets');

        // 2) 아이템 테이블 있으면 관계(entries)와 artist까지 안전하게 로드
        if (Schema::hasTable('recommendation_set_items') && $intake->recommendationSets->isNotEmpty()) {
            // 한 번에 eager load
            $intake->recommendationSets->load('entries.artist');
        }

        // 3) 최신 세트 선택(없으면 null)
        $set = method_exists($intake, 'latestSet') ? $intake->latestSet : null;
        if (!$set) {
            $set = $intake->recommendationSets->sortByDesc('id')->first();
        }

        return view('admin.recommendations.show', [
            'intake'      => $intake,
            'set'         => $set,
            'disciplines' => class_exists(Discipline::class) ? Discipline::orderBy('name')->get() : collect(),
            'genres'      => class_exists(Genre::class) ? Genre::with('discipline')->orderBy('name')->get() : collect(),
            'tags'        => class_exists(Tag::class) ? Tag::orderBy('name')->get() : collect(),
        ]);
    }

    /** 비어있는 추천셋 생성 */
    public function createSet(IntakeRequest $intake)
    {
        $data = [
            'intake_request_id' => $intake->id,
        ];

        // 옵션/레거시 JSON 컬럼 기본값
        if (Schema::hasColumn('recommendation_sets', 'options')) {
            $data['options'] = [];
        }
        if (Schema::hasColumn('recommendation_sets', 'items')) {
            $data['items'] = []; // NOT NULL 회피
        }

        // 라벨 자동 생성
        if (Schema::hasColumn('recommendation_sets', 'label')) {
            $count = RecommendationSet::where('intake_request_id', $intake->id)->count() + 1;
            $data['label'] = "옵션 {$count}";
        }

        $set = RecommendationSet::create($data);

        if (Schema::hasColumn($intake->getTable(), 'latest_set_id')) {
            $intake->forceFill(['latest_set_id' => $set->id])->save();
        }

        return redirect()->route('admin.recommendations', $intake)->with('ok', '추천셋을 생성했습니다.');
    }

    /** 추천셋에 아티스트 추가 */
    public function addItem(Request $request, IntakeRequest $intake)
    {
        $request->validate([
            'artist_id' => ['required', 'exists:artists,id'],
        ]);

        $set = method_exists($intake, 'latestSet') ? $intake->latestSet : null;
        if (!$set) {
            $set = $intake->recommendationSets()->latest('id')->first();
        }
        if (!$set) {
            return back()->with('err', '추천셋이 없습니다. 먼저 생성하세요.');
        }

        $artistId = (int) $request->input('artist_id');

        if ($set->entries()->where('artist_id', $artistId)->exists()) {
            return back()->with('err', '이미 담긴 아티스트입니다.');
        }

        $artist   = Artist::findOrFail($artistId);
        $feeRange = method_exists($artist, 'getFeeRangeAttribute') ? $artist->fee_range : null;
        $nextRank = (int) $set->entries()->max('rank') + 1;

        $meta = [];
        if (Schema::hasColumn('recommendation_set_items', 'meta')) {
            $meta = [
                'currency' => $feeRange['currency'] ?? 'KRW',
                'unit'     => $feeRange['unit'] ?? 'appearance',
            ];
        }

        $set->entries()->create([
            'artist_id'  => $artistId,
            'rank'       => $nextRank,
            'fixed'      => false,
            'excluded'   => false,
            'quoted_min' => $feeRange['min'] ?? null,
            'quoted_max' => $feeRange['max'] ?? null,
            'meta'       => $meta,
        ]);

        return back()->with('ok', '아티스트를 추천안에 담았습니다.');
    }

    /** 아이템 개별 수정 */
    public function updateItem(Request $request, IntakeRequest $intake, RecommendationSetItem $item)
    {
        $belongs = $intake->recommendationSets()->whereKey($item->recommendation_set_id)->exists();
        if (!$belongs) abort(403);

        $data = $request->validate([
            'fixed'       => ['nullable', 'boolean'],
            'excluded'    => ['nullable', 'boolean'],
            'quoted_min'  => ['nullable', 'integer', 'min:0'],
            'quoted_max'  => ['nullable', 'integer', 'min:0'],
        ]);

        $item->update([
            'fixed'       => (bool) ($data['fixed'] ?? false),
            'excluded'    => (bool) ($data['excluded'] ?? false),
            'quoted_min'  => $data['quoted_min'] ?? null,
            'quoted_max'  => $data['quoted_max'] ?? null,
        ]);

        return back()->with('ok', '저장했습니다.');
    }

    /** 아이템 삭제 */
    public function destroyItem(IntakeRequest $intake, RecommendationSetItem $item)
    {
        $belongs = $intake->recommendationSets()->whereKey($item->recommendation_set_id)->exists();
        if (!$belongs) abort(403);

        $item->delete();
        return back()->with('ok', '삭제했습니다.');
    }

    /** 순서 재정렬 */
    public function reorderItems(Request $request, IntakeRequest $intake)
    {
        $request->validate([
            'ordered_ids'   => ['required', 'array'],
            'ordered_ids.*' => ['integer', 'exists:recommendation_set_items,id'],
        ]);

        $setIds = $intake->recommendationSets()->pluck('id');
        $rank   = 1;
        foreach ($request->input('ordered_ids') as $id) {
            $item = RecommendationSetItem::whereIn('recommendation_set_id', $setIds)->find($id);
            if ($item) $item->update(['rank' => $rank++]);
        }

        return back()->with('ok', '순서를 저장했습니다.');
    }

    /** 아티스트 검색(JSON) */
    public function artistSearch(Request $request)
    {
        $filters = [
            'q'             => $request->string('q')->toString(),
            'discipline_id' => $request->integer('discipline_id') ?: null,
            'min_fame'      => $request->integer('min_fame') ?: null,
            'max_fame'      => $request->integer('max_fame') ?: null,
            'genre_ids'     => $request->input('genre_ids', []),
            'tag_ids'       => $request->input('tag_ids', []),
            'is_active'     => true,
        ];
        $budgetMin = $request->has('budget_min') ? (int) $request->input('budget_min') : null;
        $budgetMax = $request->has('budget_max') ? (int) $request->input('budget_max') : null;
        $currency  = $request->string('currency', 'KRW')->upper()->toString();

        $q = Artist::with(['genresRelation:id,name', 'tags:id,name'])
            ->filter(array_filter($filters, fn($v) => $v !== null && $v !== ''))
            ->orderByDesc('fame_score');

        if (!is_null($budgetMin) || !is_null($budgetMax)) {
            $q->where(function ($wrap) use ($budgetMin, $budgetMax, $currency) {
                $wrap->whereHas('fees', function ($fq) use ($budgetMin, $budgetMax, $currency) {
                    if ($currency) $fq->where('currency', $currency);
                    if (!is_null($budgetMin)) {
                        $fq->where(fn($qq) => $qq->whereNull('max_fee')->orWhere('max_fee', '>=', $budgetMin));
                    }
                    if (!is_null($budgetMax)) {
                        $fq->where(fn($qq) => $qq->whereNull('min_fee')->orWhere('min_fee', '<=', $budgetMax));
                    }
                })
                    ->orWhere(function ($qq) use ($budgetMin, $budgetMax) {
                        if (!is_null($budgetMin)) {
                            $qq->where(fn($w) => $w->whereNull('max_fee')->orWhere('max_fee', '>=', $budgetMin));
                        }
                        if (!is_null($budgetMax)) {
                            $qq->where(fn($w) => $w->whereNull('min_fee')->orWhere('min_fee', '<=', $budgetMax));
                        }
                    });
            });
        }

        $rows = $q->limit(30)->get()->map(function (Artist $a) {
            $fee = method_exists($a, 'getFeeRangeAttribute') ? $a->fee_range : null;
            return [
                'id'       => $a->id,
                'name'     => $a->name ?? $a->stage_name,
                'fame'     => $a->fame_score ?? null,
                'genres'   => method_exists($a, 'genresRelation') ? $a->genresRelation->pluck('name')->all() : [],
                'tags'     => method_exists($a, 'tags') ? $a->tags->pluck('name')->all() : [],
                'fee_min'  => $fee['min'] ?? ($a->fee_min ?? $a->min_fee),
                'fee_max'  => $fee['max'] ?? ($a->fee_max ?? $a->max_fee),
                'currency' => $fee['currency'] ?? 'KRW',
                'unit'     => $fee['unit'] ?? 'appearance',
            ];
        });

        return response()->json(['results' => $rows]);
    }

    /** 일괄 편집(견적 채우기 등) */
    public function bulkEdit(Request $request, IntakeRequest $intake)
    {
        $setIds = $intake->recommendationSets()->pluck('id');
        if ($setIds->isEmpty()) abort(404);

        $data = $request->validate([
            'mode'           => ['required', Rule::in(['values', 'from_fee'])],
            'scope'          => ['required', Rule::in(['all', 'empty', 'selected'])],
            'min'            => ['nullable', 'integer', 'min:0'],
            'max'            => ['nullable', 'integer', 'min:0'],
            'selected_ids'   => ['nullable', 'array'],
            'selected_ids.*' => ['integer', 'exists:recommendation_set_items,id'],
        ]);

        $itemsQ = RecommendationSetItem::whereIn('recommendation_set_id', $setIds);
        if (($data['scope'] ?? 'all') === 'selected' && !empty($data['selected_ids'])) {
            $itemsQ->whereIn('id', $data['selected_ids']);
        }
        $items = $itemsQ->with('artist')->get();

        $updated = 0;
        foreach ($items as $item) {
            if (($data['scope'] ?? 'all') === 'empty') {
                if (!is_null($item->quoted_min) || !is_null($item->quoted_max)) continue;
            }

            if ($data['mode'] === 'from_fee') {
                $fee = $item->artist?->fee_range;
                $min = $fee['min'] ?? null;
                $max = $fee['max'] ?? null;
            } else {
                $min = $data['min'] ?? null;
                $max = $data['max'] ?? null;
            }

            if ($item->quoted_min === $min && $item->quoted_max === $max) continue;

            $item->update(['quoted_min' => $min, 'quoted_max' => $max]);
            $updated++;
        }

        return back()->with('ok', "일괄 적용 완료 ({$updated}건)");
    }
}
