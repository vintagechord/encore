<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;

class PublicHomeController extends Controller
{
    public function __invoke()
    {
        $storyGroups = collect();
        try {
            if (class_exists(\App\Models\SuccessStory::class) && Schema::hasTable('success_stories')) {
                $stories = \App\Models\SuccessStory::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->orderByDesc('event_date')
                    ->orderByDesc('id')
                    ->get();
                // 홈에서는 기본으로 모든 활성 사례를 보여주고,
                // 필요 시 카테고리 탭으로 전환할 수 있도록 '전체' 그룹을 선두에 둔다.
                $grouped = $stories->groupBy(fn($s) => $s->category ?: '사례');
                if ($stories->isNotEmpty()) {
                    $storyGroups = collect(['전체' => $stories])->merge($grouped);
                } else {
                    $storyGroups = $grouped;
                }
            }
        } catch (\Throwable $e) {
            $storyGroups = collect();
        }

        $banners = collect();
        try {
            if (class_exists(\App\Models\Banner::class) && Schema::hasTable('banners')) {
                $banners = \App\Models\Banner::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->orderByDesc('id')
                    ->get();
            }
        } catch (\Throwable $e) {
            $banners = collect();
        }

        $testimonials = collect();
        try {
            if (class_exists(\App\Models\Testimonial::class) && Schema::hasTable('testimonials')) {
                $testimonials = \App\Models\Testimonial::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->orderByDesc('id')
                    ->get();
            }
        } catch (\Throwable $e) {
            $testimonials = collect();
        }

        return view('public.home', compact('storyGroups', 'banners', 'testimonials'));
    }
}
