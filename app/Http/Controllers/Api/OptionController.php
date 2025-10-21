<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GenerationRequest;
use App\Models\GenerationResult;
use App\Models\IntakeRequest;
use App\Models\RecommendationSet;
use App\Services\OptionGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    public function generate(Request $req, OptionGenerator $gen)
    {
        $payload = $this->validate($req, [
            'budget_total' => 'nullable|integer|min:0',
            'event_start'  => 'nullable|date',
            'event_end'    => 'nullable|date',
            'genre_counts' => 'required|array|min:1',
            'genre_counts.*' => 'integer|min:0',
            'locked_artist_ids' => 'array',
            'locked_artist_ids.*' => 'integer',
            'excluded_artist_ids' => 'array',
            'excluded_artist_ids.*' => 'integer',
            'option_count' => 'nullable|integer|min:1|max:8',
            'seed' => 'nullable|string|max:64',
            'intake_request_id' => 'nullable|integer|exists:intake_requests,id',
        ]);

        $gr = new GenerationRequest();
        $gr->fill($payload);
        $gr->client_ip = $req->ip();
        $gr->save();

        $options = $gen->generate($gr);
        foreach ($options as $idx => $opt) {
            GenerationResult::create([
                'generation_request_id' => $gr->id,
                'option_index' => $idx,
                'artist_ids' => $opt['artist_ids'],
                'subtotal' => $opt['subtotal'],
                'score_breakdown' => $opt['score_breakdown'],
            ]);
        }

        return response()->json([
            'request_id' => $gr->id,
            'options' => $options,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function regenerate(Request $req, OptionGenerator $gen)
    {
        // regenerate는 generate와 동일하지만 seed/locked/excluded만 바꿔 새 요청으로 취급
        return $this->generate($req, $gen);
    }

    public function commit(Request $req)
    {
        $data = $this->validate($req, [
            'generation_request_id' => 'required|integer|exists:generation_requests,id',
            'option_index' => 'required|integer|min:0',
            'intake_request_id' => 'required|integer|exists:intake_requests,id',
            'label' => 'nullable|string|max:120',
            'rationale' => 'nullable|string',
        ]);

        $gr = GenerationRequest::findOrFail($data['generation_request_id']);
        $res = $gr->results()->where('option_index', $data['option_index'])->firstOrFail();
        $intake = IntakeRequest::findOrFail($data['intake_request_id']);

        // RecommendationSet에 저장
        $set = DB::transaction(function () use ($intake, $res, $data) {
            $set = new \App\Models\RecommendationSet();
            $set->fill([
                'intake_request_id' => $intake->id,
                'label' => $data['label'] ?? '자동 생성 옵션',
                'total_cost' => $res->subtotal,
                'rationale' => $data['rationale'] ?? null,
                'items' => null, // artists 피벗 우선, items는 비워 둠
            ]);
            $set->save();

            // 아티스트 피벗 저장
            $rank = 1;
            foreach ($res->artist_ids as $aid) {
                $set->artists()->attach($aid, ['rank' => $rank++, 'score' => null, 'reason' => null]);
            }
            return $set;
        });

        return response()->json([
            'set_id' => $set->id,
            'admin_url' => route('admin.recommendations', ['intake' => $intake->id]),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
