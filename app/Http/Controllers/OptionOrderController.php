<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\GenerationRequest;
use App\Models\IntakeRequest;
use App\Models\RecommendationSet;
use App\Services\OptionGenerator;
use Illuminate\Http\Request;

class OptionOrderController extends Controller
{
    /**
     * 표준 폼 제출: 성공 시 의뢰내역으로 리디렉트
     */
    public function submit(Request $request, OptionGenerator $generator)
    {
        $idx  = (int) $request->input('idx', -1);
        $seed = (string) $request->input('seed', '');
        $intakeId = $request->input('intake');
        $token = $request->input('token');

        if ($idx < 0) return back()->withErrors(['idx' => '옵션 인덱스가 필요합니다.']);

        $user = $request->user();
        if (!$user) return redirect()->route('login');

        // 1) 공유 토큰 기반
        if (!empty($token)) {
            $set = RecommendationSet::with('intakeRequest')->where('public_token', $token)->first();
            if (!$set) return back()->withErrors(['order' => '추천안을 찾을 수 없습니다.']);
            $ctrl = app(\App\Http\Controllers\PublicRecommendationsController::class);
            [$options, $option] = $ctrl->buildOptions($set, $idx);
            if (!$option) return back()->withErrors(['order'=>'옵션을 찾을 수 없습니다.']);
            $intake = $this->createOptionIntake($user, [
                'event_start' => $set->intakeRequest->event_start ?? null,
                'event_end'   => $set->intakeRequest->event_end ?? null,
                'artists'     => $option['artists'] ?? [],
                'budget_min'  => (int)($option['budget_min'] ?? 0),
                'budget_max'  => (int)($option['budget_max'] ?? 0),
                'meta'        => [ 'type'=>'option_order', 'set_id'=>$set->id, 'public_token'=>$set->public_token, 'option_index'=>$idx ],
            ]);
            return redirect()->route('member.inquiries');
        }

        // 2) 비공개 추천셋 기반
        if (!empty($intakeId) && empty($seed)) {
            $base = IntakeRequest::findOrFail((int)$intakeId);
            $set = RecommendationSet::with(['intakeRequest','entries.artist'])
                ->where('intake_request_id', $base->id)
                ->latest('id')->first();
            if (!$set) return back()->withErrors(['order' => '추천셋이 없습니다.']);
            $ctrl = app(\App\Http\Controllers\PublicRecommendationsController::class);
            [$options, $option] = $ctrl->buildOptions($set, $idx);
            if (!$option) return back()->withErrors(['order'=>'옵션을 찾을 수 없습니다.']);
            $intake = $this->createOptionIntake($user, [
                'event_start' => $set->intakeRequest->event_start ?? null,
                'event_end'   => $set->intakeRequest->event_end ?? null,
                'artists'     => $option['artists'] ?? [],
                'budget_min'  => (int)($option['budget_min'] ?? 0),
                'budget_max'  => (int)($option['budget_max'] ?? 0),
                'meta'        => [ 'type'=>'option_order', 'source'=>'set', 'set_id'=>$set->id, 'option_index'=>$idx ],
            ]);
            return redirect()->route('member.inquiries');
        }

        // 3) 동적(seed) 기반
        if (!empty($intakeId) && !empty($seed)) {
            $base = IntakeRequest::findOrFail((int)$intakeId);
            $genreCounts = is_array($base->genre_counts) ? $base->genre_counts : [];
            $budgetTotal = (int)($base->budget_max ?? $base->budget_min ?? 0);
            $gr = GenerationRequest::create([
                'budget_total'         => $budgetTotal,
                'event_start'          => $base->event_start ?? now(),
                'event_end'            => $base->event_end   ?? ($base->event_start ?? now()),
                'genre_counts'         => $genreCounts,
                'locked_artist_ids'    => [],
                'excluded_artist_ids'  => [],
                'seed'                 => $seed,
                'option_count'         => max(1, $idx + 1),
            ]);
            $genOptions = $generator->generate($gr);
            $selected = $genOptions[$idx] ?? null;
            if (!$selected) return back()->withErrors(['order'=>'옵션을 찾을 수 없습니다.']);
            $artistIds = collect($selected['artist_ids'])->unique()->values();
            $artists   = Artist::whereIn('id', $artistIds)->get();
            $min = 0; $max = 0; $alist = [];
            foreach ($artists as $a) {
                $fr = $a->fee_range ?? null;
                if (is_array($fr)) { $min += (int)($fr['min'] ?? 0); $max += (int)($fr['max'] ?? 0); }
                $alist[] = [ 'title'=>$a->stage_name ?? $a->name ?? '아티스트', 'image'=>$a->image_url ?? null, 'fee_min'=>$fr['min'] ?? null, 'fee_max'=>$fr['max'] ?? null, 'artist_id'=>$a->id ];
            }
            $intake = $this->createOptionIntake($user, [
                'event_start' => $base->event_start ?? null,
                'event_end'   => $base->event_end ?? null,
                'artists'     => $alist,
                // 주문 시 표시 예산은 사용자가 문의한 범위를 그대로 사용
                'budget_min'  => (int)($base->budget_min ?? 0),
                'budget_max'  => (int)($base->budget_max ?? 0),
                'meta'        => [ 'type'=>'option_order', 'source'=>'intake', 'source_intake_id'=>$base->id, 'option_index'=>$idx, 'seed'=>$seed ],
            ]);
            return redirect()->route('member.inquiries');
        }

        return back()->withErrors(['order' => '요청 정보를 확인할 수 없습니다.']);
    }

    private function decodeJson($response): array
    {
        if (method_exists($response, 'getContent')) {
            $txt = $response->getContent();
            $arr = json_decode((string)$txt, true);
            if (is_array($arr)) return $arr;
        }
        return ['ok'=>false];
    }

    private function createOptionIntake($user, array $p): IntakeRequest
    {
        $notes = array_merge((array)($p['meta'] ?? []), [
            'artists'    => $p['artists'] ?? [],
            'budget_min' => (int)($p['budget_min'] ?? 0),
            'budget_max' => (int)($p['budget_max'] ?? 0),
        ]);

        return IntakeRequest::create([
            'contact_name'  => $user->name ?? '회원',
            'contact_email' => $user->email,
            'contact_phone' => null,
            'event_start'   => $p['event_start'] ?? null,
            'event_end'     => $p['event_end'] ?? null,
            'budget_min'    => $notes['budget_min'] ?: null,
            'budget_max'    => $notes['budget_max'] ?: null,
            'category'      => 'option',
            'genre_counts'  => [],
            'notes'         => json_encode($notes, JSON_UNESCAPED_UNICODE),
            'status'        => 'new',
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required','string'],
            'idx'   => ['required','integer','min:0','max:10']
        ]);

        $set = RecommendationSet::with('intakeRequest')->where('public_token', $request->input('token'))->first();
        if (!$set) return response()->json(['ok'=>false,'message'=>'추천안을 찾을 수 없습니다.'], 404);

        // Build options using helper in PublicRecommendationsController
        $ctrl = app(\App\Http\Controllers\PublicRecommendationsController::class);
        [$options, $option] = $ctrl->buildOptions($set, (int)$request->input('idx'));
        if (!$option) return response()->json(['ok'=>false,'message'=>'옵션을 찾을 수 없습니다.'], 404);

        $user = $request->user();
        if (!$user) return response()->json(['ok'=>false,'requires_login'=>true], 401);

        $min = (int)($option['budget_min'] ?? 0);
        $max = (int)($option['budget_max'] ?? 0);

        $notes = [
            'type' => 'option_order',
            'request_type' => 'instant',
            'set_id' => $set->id,
            'public_token' => $set->public_token,
            'option_index' => (int)$request->input('idx'),
            'artists' => $option['artists'] ?? [],
            'budget_min' => $min,
            'budget_max' => $max,
        ];

        $intake = IntakeRequest::create([
            'contact_name'  => $user->name ?? '회원',
            'contact_email' => $user->email,
            'contact_phone' => null,
            'event_start'   => $set->intakeRequest->event_start ?? null,
            'event_end'     => $set->intakeRequest->event_end ?? null,
            'budget_min'    => $min ?: null,
            'budget_max'    => $max ?: null,
            'category'      => 'option',
            'genre_counts'  => [],
            'notes'         => json_encode($notes, JSON_UNESCAPED_UNICODE),
            'status'        => 'new', // 초기 상태
        ]);

        return response()->json([
            'ok' => true,
            'intake_id' => $intake->id,
            'redirect_url' => route('member.inquiries'),
        ]);
    }

    /** 신규: 토큰 없이 intake+seed 기반으로 옵션 의뢰 생성 */
    public function storeFromIntake(Request $request, OptionGenerator $generator)
    {
        $request->validate([
            'intake' => ['required','integer','exists:intake_requests,id'],
            'idx'    => ['required','integer','min:0','max:10'],
            'seed'   => ['required','string'],
        ]);

        $intake = IntakeRequest::findOrFail((int)$request->input('intake'));

        $user = $request->user();
        if (!$user) return response()->json(['ok'=>false,'requires_login'=>true], 401);

        // 동일 옵션을 재현하기 위해 seed를 고정해 생성
        $genreCounts = is_array($intake->genre_counts) ? $intake->genre_counts : [];
        $budgetTotal = (int)($intake->budget_max ?? $intake->budget_min ?? 0);

        $gr = GenerationRequest::create([
            'budget_total'         => $budgetTotal,
            'event_start'          => $intake->event_start ?? now(),
            'event_end'            => $intake->event_end   ?? ($intake->event_start ?? now()),
            'genre_counts'         => $genreCounts,
            'locked_artist_ids'    => [],
            'excluded_artist_ids'  => [],
            'seed'                 => (string)$request->input('seed'),
            'option_count'         => max(1, ((int)$request->input('idx')) + 1),
        ]);

        $genOptions = $generator->generate($gr);
        $idx = (int)$request->input('idx');
        $selected = $genOptions[$idx] ?? null;
        if (!$selected) return response()->json(['ok'=>false,'message'=>'옵션을 찾을 수 없습니다.'], 404);

        $artistIds = collect($selected['artist_ids'])->unique()->values();
        $artists   = Artist::whereIn('id', $artistIds)->get()->keyBy('id');

        $min = (int)($intake->budget_min ?? 0);
        $max = (int)($intake->budget_max ?? 0);
        $alist = [];
        foreach ($artistIds as $aid) {
            $a = $artists->get($aid);
            if (!$a) continue;
            $fr = $a->fee_range ?? null;
            $alist[] = [
                'title'     => $a->stage_name ?? $a->name ?? '아티스트',
                'image'     => $a->image_url ?? null,
                'fee_min'   => $fr['min'] ?? null,
                'fee_max'   => $fr['max'] ?? null,
                'artist_id' => $a->id,
            ];
        }

        $notes = [
            'type' => 'option_order',
            'source' => 'intake',
            'request_type' => 'instant',
            'source_intake_id' => $intake->id,
            'option_index' => $idx,
            'artists' => $alist,
            'budget_min' => $min,
            'budget_max' => $max,
            'seed' => (string)$request->input('seed'),
        ];

        $newIntake = IntakeRequest::create([
            'contact_name'  => $user->name ?? '회원',
            'contact_email' => $user->email,
            'contact_phone' => null,
            'event_start'   => $intake->event_start ?? null,
            'event_end'     => $intake->event_end ?? null,
            'budget_min'    => $min ?: null,
            'budget_max'    => $max ?: null,
            'category'      => 'option',
            'genre_counts'  => [],
            'notes'         => json_encode($notes, JSON_UNESCAPED_UNICODE),
            'status'        => 'new',
        ]);

        return response()->json([
            'ok' => true,
            'intake_id' => $newIntake->id,
            'redirect_url' => route('member.inquiries'),
        ]);
    }

    /** 신규: 추천셋(비공개, 토큰 없음) 기반으로 의뢰 생성 */
    public function storeFromSet(Request $request)
    {
        $request->validate([
            'intake' => ['required','integer','exists:intake_requests,id'],
            'idx'    => ['required','integer','min:0','max:10'],
        ]);

        $user = $request->user();
        if (!$user) return response()->json(['ok'=>false,'requires_login'=>true], 401);

        $intake = IntakeRequest::findOrFail((int)$request->input('intake'));
        $set = RecommendationSet::with(['intakeRequest','entries.artist'])
            ->where('intake_request_id', $intake->id)
            ->latest('id')
            ->first();
        if (!$set) return response()->json(['ok'=>false, 'message'=>'추천셋이 없습니다.'], 404);

        $ctrl = app(\App\Http\Controllers\PublicRecommendationsController::class);
        [$options, $option] = $ctrl->buildOptions($set, (int)$request->input('idx'));
        if (!$option) return response()->json(['ok'=>false,'message'=>'옵션을 찾을 수 없습니다.'], 404);

        $min = (int)($option['budget_min'] ?? 0);
        $max = (int)($option['budget_max'] ?? 0);

        $notes = [
            'type' => 'option_order',
            'source' => 'set',
            'request_type' => 'instant',
            'set_id' => $set->id,
            'public_token' => $set->public_token,
            'option_index' => (int)$request->input('idx'),
            'artists' => $option['artists'] ?? [],
            'budget_min' => $min,
            'budget_max' => $max,
        ];

        $newIntake = IntakeRequest::create([
            'contact_name'  => $user->name ?? '회원',
            'contact_email' => $user->email,
            'contact_phone' => null,
            'event_start'   => $set->intakeRequest->event_start ?? null,
            'event_end'     => $set->intakeRequest->event_end ?? null,
            'budget_min'    => $min ?: null,
            'budget_max'    => $max ?: null,
            'category'      => 'option',
            'genre_counts'  => [],
            'notes'         => json_encode($notes, JSON_UNESCAPED_UNICODE),
            'status'        => 'new',
        ]);

        return response()->json([
            'ok' => true,
            'intake_id' => $newIntake->id,
            'redirect_url' => route('member.inquiries'),
        ]);
    }
}
