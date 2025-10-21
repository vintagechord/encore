<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;

class PublicHomeController extends Controller
{
    public function __invoke()
    {
        $stories = SuccessStory::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderByDesc('event_date')
            ->orderByDesc('id')
            ->get();

        $grouped = $stories->groupBy(fn ($story) => $story->category ?: '섭외 확정');

        return view('public.home', [
            'storyGroups' => $grouped,
        ]);
    }
}
