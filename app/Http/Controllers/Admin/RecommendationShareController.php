<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeRequest;
use App\Models\RecommendationSet;
use App\Models\ShareTokenEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class RecommendationShareController extends Controller
{
    // 최초 공유(토큰 없으면 발급, 있으면 그대로 사용) + sent_at 갱신
    public function __invoke(IntakeRequest $intake, Request $req)
    {
        $set = RecommendationSet::where('intake_request_id', $intake->id)
            ->latest('id')->first();

        if (!$set) {
            return response()->json(['message' => '추천안이 없습니다.'], 404);
        }

        $wasEmpty = empty($set->public_token);
        if ($wasEmpty) {
            $set->public_token = (string) Str::ulid();
        }

        $set->sent_at = Carbon::now('UTC');
        $set->save();

        if ($wasEmpty) {
            ShareTokenEvent::create([
                'recommendation_set_id' => $set->id,
                'token'                  => $set->public_token,
                'event_type'             => 'issued',
            ]);
        }

        return response()->json([
            'url'     => $set->publicUrl(),
            'sent_at' => $set->sent_at?->toIso8601String(),
        ], 200);
    }

    // 토큰 회전
    public function rotate(IntakeRequest $intake, Request $req)
    {
        $set = RecommendationSet::where('intake_request_id', $intake->id)
            ->latest('id')->first();

        if (!$set) {
            return response()->json(['message' => '추천안이 없습니다.'], 404);
        }

        // 이전 토큰 기록용 (필요시 사용)
        $oldToken = $set->public_token;

        // 새 토큰 발급 + 발송시각
        $set->public_token = (string) Str::ulid();
        $set->sent_at = Carbon::now('UTC');
        $set->save();

        // 회전 이벤트 기록(신규 토큰 기준으로 저장)
        ShareTokenEvent::create([
            'recommendation_set_id' => $set->id,
            'token'                  => $set->public_token,
            'event_type'             => 'rotated',
        ]);

        return response()->json([
            'url'     => $set->publicUrl(),
            'sent_at' => $set->sent_at?->toIso8601String(),
            'rotated' => true,
        ], 200);
    }

    // 공유 중단(토큰 무효화)
    public function revoke(IntakeRequest $intake, Request $req)
    {
        $set = RecommendationSet::where('intake_request_id', $intake->id)
            ->latest('id')->first();

        if (!$set || empty($set->public_token)) {
            return response()->json(['message' => '현재 공유 중이 아닙니다.'], 400);
        }

        $old = $set->public_token;

        // 토큰 제거
        $set->public_token = null;
        $set->save();

        // 중단 이벤트 기록(중단 당시 토큰 저장)
        ShareTokenEvent::create([
            'recommendation_set_id' => $set->id,
            'token'                  => $old,
            'event_type'             => 'revoked',
        ]);

        return response()->json([
            'revoked'    => true,
            'revoked_at' => now('UTC')->toIso8601String(),
        ], 200);
    }

    // ✅ (신규) 공유 이력 조회(JSON)
    public function history(IntakeRequest $intake, Request $req)
    {
        $set = RecommendationSet::where('intake_request_id', $intake->id)
            ->latest('id')->first();

        if (!$set) {
            return response()->json(['message' => '추천안이 없습니다.'], 404);
        }

        $limit = (int) $req->integer('limit', 20);
        $limit = max(1, min(100, $limit)); // 1~100 범위로 클램프

        $events = ShareTokenEvent::where('recommendation_set_id', $set->id)
            ->orderByDesc('id')
            ->limit($limit)
            ->get(['id', 'event_type', 'token', 'created_at'])
            ->map(function ($e) {
                return [
                    'id'         => $e->id,
                    'event_type' => $e->event_type,   // issued | rotated | revoked | shared 등
                    'token'      => $e->token,
                    'created_at' => $e->created_at?->toIso8601String(),
                ];
            });

        return response()->json([
            'intake_id'           => $intake->id,
            'recommendation_set'  => $set->id,
            'count'               => $events->count(),
            'events'              => $events,
        ], 200);
    }
}
