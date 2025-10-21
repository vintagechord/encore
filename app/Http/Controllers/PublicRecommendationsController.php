<?php

namespace App\Http\Controllers;

use App\Models\RecommendationSet;

class PublicRecommendationsController extends Controller
{
    public function show(string $token)
    {
        $set = RecommendationSet::with('intakeRequest')
            ->where('public_token', $token)
            ->first();

        // 토큰으로 찾지 못하면: 410 Gone + 안내 페이지
        if (!$set) {
            return response()
                ->view('inquiry.link_gone', [
                    'token' => $token,
                ], 410);
        }

        return view('inquiry.recommendations', [
            'set'    => $set,
            'intake' => $set->intakeRequest,
        ]);
    }
}
