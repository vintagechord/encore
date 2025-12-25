<?php

namespace App\Http\Controllers;

use App\Models\RecommendationSet;
use Illuminate\Support\Collection;
use App\Models\Artist;

class PublicRecommendationsController extends Controller
{
    public function show(string $token)
    {
        $set = RecommendationSet::with(['intakeRequest','entries.artist'])
            ->where('public_token', $token)
            ->first();

        // 토큰으로 찾지 못하면: 410 Gone + 안내 페이지
        if (!$set) {
            return response()
                ->view('inquiry.link_gone', [
                    'token' => $token,
                ], 410);
        }

        [$options] = $this->buildOptions($set, 0);
        return view('inquiry.select_options', [
            'set'    => $set,
            'intake' => $set->intakeRequest,
            'options' => $options,
        ]);
    }

    /**
     * 옵션 리포트 보기
     */
    public function showOption(string $token, int $idx)
    {
        $set = RecommendationSet::with(['intakeRequest','entries.artist'])
            ->where('public_token', $token)
            ->first();

        if (!$set) {
            return response()->view('inquiry.link_gone', ['token'=>$token], 410);
        }

        [$options, $option] = $this->buildOptions($set, $idx);
        if (!$option) abort(404);

        return view('inquiry.option_report', [
            'set' => $set,
            'intake' => $set->intakeRequest,
            'option' => $option,
            'options' => $options,
            'index' => $idx,
        ]);
    }

    /**
     * 추천셋에서 3개 옵션을 구성해 반환
     * returns: [options(array), optionAtIdx|null]
     */
    public function buildOptions(RecommendationSet $set, int $targetIndex = 0): array
    {
        $items = collect();
        // collect from legacy JSON
        $raw = is_array($set->items ?? null) ? $set->items : (is_string($set->items ?? null) ? json_decode($set->items, true) : []);
        if (is_array($raw) && $raw) {
            foreach ($raw as $it) $items->push($it);
        }
        // else from entries
        if ($items->isEmpty()) {
            foreach ($set->entries as $e) {
                $artist = $e->artist;
                $items->push([
                    'artist_id' => $artist?->id,
                    'title' => $artist?->stage_name ?? $artist?->name ?? '아티스트',
                    'image' => $artist?->image_url ?? null,
                    'rank' => $e->rank ?? null,
                    'fee_min' => $e->quoted_min ?? ($artist?->fee_range['min'] ?? null),
                    'fee_max' => $e->quoted_max ?? ($artist?->fee_range['max'] ?? null),
                    'discipline' => $artist?->discipline?->name ?? null,
                ]);
            }
        }
        // sort by rank
        $items = $items->sortBy(fn($x)=>$x['rank'] ?? 9999)->values();

        // Simple bundling: distribute into 3 options satisfying multi-team per category if possible
        $bundles = [collect(), collect(), collect()];
        $i = 0;
        foreach ($items as $it) { $bundles[$i%3]->push($it); $i++; }
        // ensure not empty
        for ($j=0;$j<3;$j++) if ($bundles[$j]->isEmpty()) $bundles[$j]->push(['title'=>'후보 준비중']);

        $options = [];
        foreach ($bundles as $k=>$bundle) {
            $min = 0; $max = 0; $artists = [];
            foreach ($bundle as $it) {
                $t = (string)($it['title'] ?? '아티스트');
                $artists[] = [
                    'title' => $t,
                    'image' => $it['image'] ?? null,
                    'fee_min' => $it['fee_min'] ?? null,
                    'fee_max' => $it['fee_max'] ?? null,
                    'artist_id' => $it['artist_id'] ?? null,
                ];
                $min += (int)($it['fee_min'] ?? 0);
                $max += (int)($it['fee_max'] ?? 0);
            }
            // ensure at least 3 artists by sampling
            if (count($artists) < 3) {
                $need = 3 - count($artists);
                try {
                    $extras = Artist::inRandomOrder()->take($need)->get();
                    foreach ($extras as $ar) {
                        $fr = $ar->fee_range ?? null;
                        $artists[] = [
                            'title' => $ar->name ?? $ar->stage_name,
                            'image' => $ar->image_url ?? null,
                            'fee_min' => $fr['min'] ?? null,
                            'fee_max' => $fr['max'] ?? null,
                            'artist_id' => $ar->id,
                        ];
                        $min += (int)($fr['min'] ?? 0);
                        $max += (int)($fr['max'] ?? 0);
                    }
                } catch (\Throwable $e) { /* ignore if table missing */ }
            }
            $options[$k] = [
                'index' => $k,
                'label' => '옵션 '.($k+1),
                'artists' => $artists,
                'budget_min' => $min ?: null,
                'budget_max' => $max ?: null,
            ];
        }

        $option = $options[$targetIndex] ?? null;
        return [$options, $option];
    }
}
