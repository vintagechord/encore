<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicRecommendationsController;
use App\Http\Controllers\Admin\RecommendationController as AdminRecommendationController;
use App\Http\Controllers\Admin\RecommendationShareController as AdminRecommendationShareController;
use App\Http\Controllers\Admin\RecommendationExportController as AdminRecommendationExportController;
use App\Http\Controllers\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Admin\TaxonomyController as AdminTaxonomyController;
use App\Http\Controllers\Admin\SuccessStoryController as AdminSuccessStoryController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\DirectRequestController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ArtistPublicController;
use App\Http\Controllers\OptionOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NoticeController as PublicNoticeController;
use App\Http\Controllers\FavoriteController;

Route::get('/healthz', function () {
    return response('ok', 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8')
        ->header('Cache-Control', 'no-store');
})->withoutMiddleware([
    \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
]);

Route::get('/', PublicHomeController::class)->name('home');
// FAQ & Notices
Route::view('/about', 'public.about')->name('about');
Route::view('/faq', 'public.faq')->name('faq');
Route::get('/notices', [PublicNoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{notice}', [PublicNoticeController::class, 'show'])->name('notices.show');

// 공개 추천안(토큰)
Route::get('/r/{token}', [PublicRecommendationsController::class, 'show'])->name('share.token');
Route::get('/r/{token}/option/{idx}', [PublicRecommendationsController::class, 'showOption'])
    ->where(['idx'=>'[0-9]+'])
    ->name('share.token.option');

// 공개 아티스트 탐색
Route::get('/artists', [ArtistPublicController::class, 'index'])->name('artists.browse');
Route::get('/artists/{discipline}', [ArtistPublicController::class, 'index'])
    ->name('artists.browse.discipline');

// 공개 아티스트 상세
Route::get('/artist/{artist}', [ArtistPublicController::class, 'show'])->name('artist.show');

// 옵션 의뢰(로그인 필요): AJAX JSON
Route::post('/order/option', [OptionOrderController::class, 'store'])->middleware('auth')->name('order.option');
// 신규: 토큰 없이 intake+seed 기반으로 생성
Route::post('/order/option/intake', [OptionOrderController::class, 'storeFromIntake'])->middleware('auth')->name('order.option.intake');
// 신규: 토큰 없이, 생성된 추천셋 기준으로 생성
Route::post('/order/option/set', [OptionOrderController::class, 'storeFromSet'])->middleware('auth')->name('order.option.set');
// 표준 폼 제출(리디렉트) 방식
Route::post('/order/option/submit', [OptionOrderController::class, 'submit'])->middleware('auth')->name('order.option.submit');

// 결제
Route::middleware('auth')->group(function(){
    Route::get('/pay/{intake}', [PaymentController::class, 'create'])->name('pay.create');
    Route::post('/pay/{intake}', [PaymentController::class, 'store'])->name('pay.store');
    Route::get('/payment/{payment}', [PaymentController::class, 'show'])->name('payment.show');
});

// 공유 예시: 실제 공유 가능한 최신 토큰으로 리디렉트, 없으면 샘플 뷰
Route::get('/examples/share', function () {
    $set = \App\Models\RecommendationSet::with('intake')
        ->whereNotNull('public_token')
        ->latest('id')
        ->first();
    if ($set && !empty($set->public_token)) {
        return redirect('/r/' . $set->public_token);
    }
    // 토큰이 없으면 최신 셋으로 렌더, 그것마저 없으면 샘플 뷰
    $fallback = \App\Models\RecommendationSet::with('intake')->latest('id')->first();
    if ($fallback) {
        return view('inquiry.select_options', [
            'set' => $fallback,
            'intake' => $fallback->intake,
        ]);
    }
    return view('public.share_example');
})->name('share.example');

Route::get('/dashboard', function () {
    return redirect()->route('member.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member area
    Route::get('/me', [MemberController::class, 'dashboard'])->name('member.dashboard');
    Route::get('/me/inquiries', [MemberController::class, 'inquiries'])->name('member.inquiries');
    Route::get('/me/payments', [MemberController::class, 'payments'])->name('member.payments');
    Route::get('/me/favorites', [MemberController::class, 'favorites'])->name('member.favorites');

    // Member private recommendations (no public token required)
    Route::get('/me/recommendations/{intake}', [MemberController::class, 'recommendations'])->name('member.recommendations');
    Route::get('/me/recommendations/{intake}/option/{idx}', [MemberController::class, 'recommendationOption'])
        ->whereNumber('idx')
        ->name('member.recommendations.option');
});

Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry.create');
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');

// 1일 Set 문의
Route::get('/inquiry/one-day', [InquiryController::class, 'oneDayCreate'])->name('inquiry.one_day.create');
Route::post('/inquiry/one-day', [InquiryController::class, 'oneDayStore'])->name('inquiry.one_day.store');

Route::get('/inquiry/{intake}/options', [InquiryController::class, 'options'])
    ->whereNumber('intake')
    ->name('inquiry.options');

// 신규 사용자용 옵션 선택 뷰
Route::get('/inquiry/{intake}/select-options', [InquiryController::class, 'selectOptions'])
    ->whereNumber('intake')
    ->name('inquiry.select_options');

// 신규: 옵션 상세 미리보기(토큰 없이도 접근)
Route::get('/inquiry/{intake}/option/{idx}', [InquiryController::class, 'previewOption'])
    ->whereNumber('intake')
    ->whereNumber('idx')
    ->name('inquiry.option.preview');

// (없으면 같이) 감사 페이지 라우트
Route::get('/inquiry/thanks', [InquiryController::class, 'thanks'])->name('inquiry.thanks');

// Direct request (specific artist)
Route::get('/request/artist', [DirectRequestController::class, 'create'])->name('direct.request.create');
Route::post('/request/artist', [DirectRequestController::class, 'store'])->name('direct.request.store');

// Favorites
Route::post('/favorites/{artist}', [FavoriteController::class, 'store'])->middleware('auth')->name('favorites.store');
Route::delete('/favorites/{artist}', [FavoriteController::class, 'destroy'])->middleware('auth')->name('favorites.destroy');

// ===== Admin =====
// Change admin URL to `/eadmincore` and gate with simple password
Route::prefix('eadmincore')->name('admin.')->group(function () {
    // Unlock page (no gate)
    Route::match(['get','post'], '/unlock', function (Illuminate\Http\Request $request) {
        $error = null;
        $redirectTo = $request->input('return') ?: route('admin.home');
        if ($request->isMethod('post')) {
            $password = (string)($request->input('password') ?? '');
            $expected = env('ADMIN_PASSWORD', '90359035jyjy');
            if (hash_equals((string)$expected, $password)) {
                $request->session()->put('admin_unlocked', true);
                return redirect()->to($redirectTo);
            }
            $error = '비밀번호가 올바르지 않습니다.';
        }
        return response()->view('admin.unlock', [
            'error' => $error,
            'return' => $redirectTo,
        ]);
    })->name('unlock');

    // All admin pages require the gate
    Route::middleware('admin.gate')->group(function(){
    // Admin home
    Route::get('/', \App\Http\Controllers\Admin\HomeController::class)->name('home');
    // 문의 목록
    Route::get('/intakes', function () {
        $intakes = \App\Models\IntakeRequest::withLatestFlags()
            ->withCount('recommendationSets')
            ->latest('id')
            ->limit(200)
            ->get();
        return view('admin.intakes', compact('intakes'));
    })->name('intakes');
    Route::post('/intakes/{intake}/status', [\App\Http\Controllers\Admin\IntakeStatusController::class, 'update'])->name('intakes.status');

    // 내보내기
    Route::get('/intakes/export', \App\Http\Controllers\Admin\IntakeExportController::class)->name('intakes.export');

    // 추천안 상세/편집
    Route::get('/recommendations/{intake}', [AdminRecommendationController::class, 'show'])->name('recommendations');
    Route::post('/recommendations/{intake}/create-set', [AdminRecommendationController::class, 'createSet'])->name('recommendations.create_set');

    // 추천안 아이템
    Route::post('/recommendations/{intake}/items', [AdminRecommendationController::class, 'addItem'])->name('recommendations.items.store');
    Route::patch('/recommendations/{intake}/items/{item}', [AdminRecommendationController::class, 'updateItem'])->name('recommendations.items.update');
    Route::delete('/recommendations/{intake}/items/{item}', [AdminRecommendationController::class, 'destroyItem'])->name('recommendations.items.destroy');
    Route::post('/recommendations/{intake}/items/reorder', [AdminRecommendationController::class, 'reorderItems'])->name('recommendations.items.reorder');
    Route::post('/recommendations/{intake}/items/bulk', [AdminRecommendationController::class, 'bulkEdit'])->name('recommendations.items.bulk');

    // 추천안 Export(JSON 뷰용)
    Route::get('/recommendations/{intake}/json', function (\App\Models\IntakeRequest $intake) {
        $set = \App\Models\RecommendationSet::with(['entries.artist'])
            ->where('intake_request_id', $intake->id)
            ->latest('id')
            ->first();
        if (!$set) return response()->json(['message' => '추천안이 없습니다.'], 404);
        return response()->json($set);
    })->name('recommendations.json');

    // 공유 관련
    Route::post('/recommendations/{intake}/share', [AdminRecommendationShareController::class, '__invoke'])->name('recommendations.share');
    Route::post('/recommendations/{intake}/rotate', [AdminRecommendationShareController::class, 'rotate'])->name('recommendations.rotate');
    Route::post('/recommendations/{intake}/revoke', [AdminRecommendationShareController::class, 'revoke'])->name('recommendations.revoke');
    Route::get('/recommendations/{intake}/share-log', [AdminRecommendationShareController::class, 'history'])->name('recommendations.share_log');

    // 아티스트
    Route::get('/artists', [AdminArtistController::class, 'index'])->name('artists.index');
    Route::get('/artists/create', [AdminArtistController::class, 'create'])->name('artists.create');
    Route::post('/artists', [AdminArtistController::class, 'store'])->name('artists.store');
    Route::get('/artists/{artist}/edit', [AdminArtistController::class, 'edit'])->name('artists.edit');
    // Accept both PUT and PATCH for broader form compatibility
    Route::match(['put','patch'], '/artists/{artist}', [AdminArtistController::class, 'update'])->name('artists.update');
    Route::delete('/artists/{artist}', [AdminArtistController::class, 'destroy'])->name('artists.destroy');
    // 샘플 아티스트 생성(분야별 최대 per명까지 채움)
    Route::post('/artists/seed', [AdminArtistController::class, 'seed'])->name('artists.seed');
    // 실존 인물/팀 기반 20명/분야 배치(기본 전체 교체)
    Route::post('/artists/seed-real', [AdminArtistController::class, 'seedReal'])->name('artists.seed_real');
    // CSV Import
    Route::get('/artists/import', [AdminArtistController::class, 'importForm'])->name('artists.import_form');
    Route::post('/artists/import', [AdminArtistController::class, 'import'])->name('artists.import');
    Route::get('/artist-search', [AdminRecommendationController::class, 'artistSearch'])->name('artist_search');

    // 분류 관리(분야/장르/태그)
    Route::get('/taxonomies', [AdminTaxonomyController::class, 'index'])->name('taxonomies.index');
    Route::post('/taxonomies', [AdminTaxonomyController::class, 'store'])->name('taxonomies.store');
    Route::put('/taxonomies/{type}/{id}', [AdminTaxonomyController::class, 'update'])->name('taxonomies.update');
    Route::delete('/taxonomies/{type}/{id}', [AdminTaxonomyController::class, 'destroy'])->name('taxonomies.destroy');
    Route::post('/taxonomies/seed-defaults', [AdminTaxonomyController::class, 'seedDefaults'])->name('taxonomies.seed');

    // 섭외 사례 관리
    Route::get('/success-stories', [AdminSuccessStoryController::class, 'index'])->name('success-stories.index');
    Route::get('/success-stories/create', [AdminSuccessStoryController::class, 'create'])->name('success-stories.create');
    Route::post('/success-stories', [AdminSuccessStoryController::class, 'store'])->name('success-stories.store');
    Route::get('/success-stories/{success_story}/edit', [AdminSuccessStoryController::class, 'edit'])->name('success-stories.edit');
    // Accept both PUT and PATCH for broader form compatibility
    Route::match(['put','patch'], '/success-stories/{success_story}', [AdminSuccessStoryController::class, 'update'])->name('success-stories.update');
    Route::delete('/success-stories/{success_story}', [AdminSuccessStoryController::class, 'destroy'])->name('success-stories.destroy');

    // 샘플 문의/후기
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [AdminTestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [AdminTestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::patch('/testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // 배너 관리
    Route::get('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [\App\Http\Controllers\Admin\BannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [\App\Http\Controllers\Admin\BannerController::class, 'edit'])->name('banners.edit');
    Route::patch('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('banners.destroy');

    // 공지사항
    Route::get('/notices', [\App\Http\Controllers\Admin\NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/create', [\App\Http\Controllers\Admin\NoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices', [\App\Http\Controllers\Admin\NoticeController::class, 'store'])->name('notices.store');
    Route::get('/notices/{notice}/edit', [\App\Http\Controllers\Admin\NoticeController::class, 'edit'])->name('notices.edit');
    Route::patch('/notices/{notice}', [\App\Http\Controllers\Admin\NoticeController::class, 'update'])->name('notices.update');
    Route::delete('/notices/{notice}', [\App\Http\Controllers\Admin\NoticeController::class, 'destroy'])->name('notices.destroy');
    });
});

require __DIR__ . '/auth.php';
