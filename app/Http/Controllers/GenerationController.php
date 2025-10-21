<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\IntakeRequest;
use App\Models\GenerationRequest;
use App\Services\OptionGenerator;
use Illuminate\Http\Request;

class GenerationController extends Controller
{
    // 미리보기 페이지
    public function show(IntakeRequest $intake)
    {
        return view('inquiry.options', compact('intake'));
    }

    // 옵션 생성(재생성) API
    public function generate(IntakeRequest $intake, Request $request, OptionGenerator $generator)
    {
        $optionCount = max(1, min(6, (int) $request->integer('option_count', 3)));
        $locked = array_values(array_filter((array) $request->input('locked_artist_ids', []), 'is_numeric'));
        $excluded = array_values(array_filter((array) $request->input('excluded_artist_ids', []), 'is_numeric'));

        // 예산: 최대값 있으면 우선, 없으면 최소값 사용
        $budgetTotal = $intake->budget_max ?? $intake->budget_min ?? 0;

        $gr = GenerationRequest::create([
            'budget_total'        => (int) $budgetTotal,
            'event_start'         => $intake->event_start,
            'event_end'           => $intake->event_end ?? $intake->event_start,
            'genre_counts'        => (array) ($intake->genre_counts ?? []),
            'locked_artist_ids'   => $locked,
            'excluded_artist_ids' => $excluded,
            'seed'                => (string) ($request->input('seed') ?? substr(bin2hex(random_bytes(4)), 0, 8)),
            'option_count'        => $optionCount,
        ]);

        $options = $generator->generate($gr);

        // 아티스트 상세 붙이기
        $allIds = [];
        foreach ($options as $o) {
            foreach (($o['artist_ids'] ?? []) as $id) $allIds[] = (int) $id;
        }
        $artists = Artist::whereIn('id', array_unique($allIds))->get()->keyBy('id');

        $decorated = array_map(function ($o) use ($artists) {
            $rows = [];
            foreach (($o['artist_ids'] ?? []) as $id) {
                $a = $artists[$id] ?? null;
                if ($a) {
                    $rows[] = [
                        'id'         => $a->id,
                        'stage_name' => $a->stage_name,
                        'min_fee'    => $a->min_fee,
                        'max_fee'    => $a->max_fee,
                        'genres'     => $a->genres,
                    ];
                }
            }
            return [
                'artist_ids'     => array_map('intval', $o['artist_ids'] ?? []),
                'artists'        => $rows,
                'subtotal'       => (int) ($o['subtotal'] ?? 0),
                'score_breakdown' => $o['score_breakdown'] ?? [],
            ];
        }, $options);

        return response()->json([
            'generation_id' => $gr->id,
            'options'       => $decorated,
        ]);
    }
}
