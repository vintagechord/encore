<?php

namespace App\Services;

use App\Models\{Artist, IntakeRequest, RecommendationSet};

class RecommendationEngine
{
    public function generate(IntakeRequest $intake): array
    {
        $budgetMax = $intake->budget_max ?? $intake->budget_min;
        $genres = $intake->genres ?? [];
        $moods  = $intake->moods ?? [];
        $format = $intake->performance_type;
        $want   = trim((string)$intake->requested_artist_name);

        // 전체 로스터 불러와 간단 스코어링
        $candidates = Artist::where('active', true)->get();

        $scoreFn = function (Artist $a) use ($genres, $moods, $format, $want) {
            $score = 0;
            foreach ($genres as $g) if (in_array($g, $a->genres ?? [])) $score += 2;
            foreach ($moods  as $m) if (in_array($m, $a->moods  ?? [])) $score += 1;
            if ($format && in_array($format, $a->formats ?? [])) $score += 2;
            if ($want && stripos($a->stage_name, $want) !== false) $score += 3;
            return $score;
        };

        $estFn = function (Artist $a) use ($budgetMax) {
            $base = $a->min_fee ?? 0;
            if ($a->max_fee && $budgetMax && $a->max_fee <= $budgetMax) {
                $base = (int)(($a->min_fee + $a->max_fee) / 2);
            }
            return max(0, $base);
        };

        $ranked = $candidates->map(fn($a) => [
            'artist' => $a,
            'score' => $scoreFn($a),
            'fee'  => $estFn($a),
        ])->sortByDesc('score')->values();

        $sets = [];

        // A) 단독
        if ($ranked->count()) {
            $top = $ranked->first();
            $sets[] = [
                'label' => 'A) 단독 구성',
                'items' => [['artist_id' => $top['artist']->id, 'name' => $top['artist']->stage_name, 'est_fee' => $top['fee']]],
                'total_cost' => $top['fee'],
                'rationale' => '선호 조건과 가장 높은 매칭',
            ];
        }

        // B) 투 라인업
        if ($ranked->count() > 1) {
            $two = $ranked->take(2);
            $items = [];
            $total = 0;
            foreach ($two as $it) {
                $items[] = ['artist_id' => $it['artist']->id, 'name' => $it['artist']->stage_name, 'est_fee' => $it['fee']];
                $total  += $it['fee'];
            }
            $sets[] = ['label' => 'B) 듀오/투 라인업', 'items' => $items, 'total_cost' => $total, 'rationale' => '분위기 다양화'];
        }

        // C) 예산 친화(최저가)
        $cheap = $ranked->sortBy('fee')->first();
        if ($cheap) {
            $sets[] = [
                'label' => 'C) 예산 친화',
                'items' => [['artist_id' => $cheap['artist']->id, 'name' => $cheap['artist']->stage_name, 'est_fee' => $cheap['fee']]],
                'total_cost' => $cheap['fee'],
                'rationale' => '최소 비용 구성',
            ];
        }

        // 저장
        $saved = [];
        foreach ($sets as $s) {
            $saved[] = RecommendationSet::create([
                'intake_request_id' => $intake->id,
                'label' => $s['label'],
                'items' => $s['items'],
                'total_cost' => $s['total_cost'],
                'rationale' => $s['rationale'],
            ]);
        }
        return $saved;
    }
}
