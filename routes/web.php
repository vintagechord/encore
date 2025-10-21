<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PublicRecommendationsController;

use App\Http\Controllers\Admin\IntakeExportController;
use App\Http\Controllers\Admin\RecommendationShareController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\TaxonomyController;
use App\Http\Controllers\Admin\RecommendationController;
use App\Http\Controllers\Admin\SuccessStoryController;

use App\Models\IntakeRequest;
use App\Models\RecommendationSet;

/*
|--------------------------------------------------------------------------
| Global Constraints
|--------------------------------------------------------------------------
*/

Route::pattern('intake', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Public / Guest
|--------------------------------------------------------------------------
*/

// 퍼블릭 홈(필요 시 뷰 준비)
Route::get('/', PublicHomeController::class)->name('home');

// 문의 작성/저장/감사
Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry.create');
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');
Route::get('/inquiry/thanks', [InquiryController::class, 'thanks'])->name('inquiry.thanks');

// 고객용: 옵션 미리보기(재생성 지원)
Route::get('/inquiry/{intake}/options', [InquiryController::class, 'options'])
    ->name('inquiry.options');

// 공개 추천안 링크 (비로그인)
Route::get('/r/{token}', [PublicRecommendationsController::class, 'show'])
    ->name('recommendations.public.show');

/*
|--------------------------------------------------------------------------
| Auth Scaffolding
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Admin (protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // /admin -> 문의 목록으로
    Route::get('/', fn() => redirect()->route('admin.intakes'))->name('root');

    /*
    |------------------------------------
    | 문의/추천안 목록 + Export/JSON
    |------------------------------------
    */
    // 일괄 편집(견적 채우기 등)
    Route::post('recommendations/{intake}/items/bulk', [RecommendationController::class, 'bulkEdit'])
        ->name('recommendations.items.bulk');

    // 관리자용 문의 목록(HTML) — ★ 필터 반영(q/from/to/limit)
    Route::get('/intakes/list', function () {
        $q     = trim((string) request('q', ''));
        $from  = request('from');
        $to    = request('to');
        $limit = max(1, min(200, (int) request()->integer('limit', 50)));

        $query = \App\Models\IntakeRequest::query()
            ->select(['intake_requests.id', 'contact_name', 'contact_email', 'created_at'])
            ->addSelect([
                'latest_sent_at' => \App\Models\RecommendationSet::select('sent_at')
                    ->whereColumn('intake_request_id', 'intake_requests.id')
                    ->orderByDesc('id')->limit(1),
                'latest_token'   => \App\Models\RecommendationSet::select('public_token')
                    ->whereColumn('intake_request_id', 'intake_requests.id')
                    ->orderByDesc('id')->limit(1),
                'latest_set_id'  => \App\Models\RecommendationSet::select('id')
                    ->whereColumn('intake_request_id', 'intake_requests.id')
                    ->orderByDesc('id')->limit(1),
            ]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('contact_name', 'like', "%{$q}%")
                    ->orWhere('contact_email', 'like', "%{$q}%");
            });
        }
        if ($from) $query->whereDate('intake_requests.created_at', '>=', $from);
        if ($to)   $query->whereDate('intake_requests.created_at', '<=', $to);

        $intakes = $query->latest('intake_requests.id')->limit($limit)->get();

        return view('admin.intakes', compact('intakes'));
    })->name('intakes');

    // CSV 내보내기
    Route::get('/intakes/export.csv', IntakeExportController::class)
        ->name('intakes.export');

    // 과거 뷰 호환용 별칭
    Route::get('/intakes/{intake}/export.csv', IntakeExportController::class)
        ->name('recommendations.export');

    // 최신 문의 리스트(JSON)
    Route::get('/intakes', function () {
        $intakes = \App\Models\IntakeRequest::latest()
            ->select(['id', 'contact_name', 'contact_email', 'created_at'])
            ->limit(50)->get();

        $flags = JSON_UNESCAPED_UNICODE | (request('pretty') ? JSON_PRETTY_PRINT : 0);
        return response()->json($intakes, 200, [], $flags);
    })->name('intakes.json');

    // 특정 문의의 추천안(JSON)
    Route::get('/intakes/{intake}/recommendations.json', function (IntakeRequest $intake) {
        $sets = RecommendationSet::where('intake_request_id', $intake->id)->get(['label', 'total_cost', 'items']);

        $payload = [
            'intake' => [
                'id'            => $intake->id,
                'contact_name'  => $intake->contact_name,
                'contact_email' => $intake->contact_email,
                'created_at'    => $intake->created_at->toIso8601String(),
            ],
            'recommendations' => $sets->map(function ($s) {
                $items = is_string($s->items) ? json_decode($s->items, true) : $s->items;
                return [
                    'label'      => $s->label,
                    'total_cost' => $s->total_cost,
                    'items'      => $s->items,
                ];
            })->values(),
        ];

        $flags = JSON_UNESCAPED_UNICODE | (request('pretty') ? JSON_PRETTY_PRINT : 0);
        return response()->json($payload, 200, [], $flags);
    })->name('recommendations.json');

    /*
    |------------------------------------
    | 추천안 편집(아티스트 담기) 빌더
    |------------------------------------
    */

    // 추천안 상세(빌더)
    Route::get('recommendations/{intake}', [RecommendationController::class, 'show'])
        ->name('recommendations');

    // 비어있는 추천셋 생성
    Route::post('recommendations/{intake}/create', [RecommendationController::class, 'createSet'])
        ->name('recommendations.create_set');

    // 아이템 추가/수정/삭제/정렬
    Route::post('recommendations/{intake}/items', [RecommendationController::class, 'addItem'])
        ->name('recommendations.items.store');

    Route::patch('recommendations/{intake}/items/{item}', [RecommendationController::class, 'updateItem'])
        ->name('recommendations.items.update');

    Route::delete('recommendations/{intake}/items/{item}', [RecommendationController::class, 'destroyItem'])
        ->name('recommendations.items.destroy');

    Route::post('recommendations/{intake}/items/reorder', [RecommendationController::class, 'reorderItems'])
        ->name('recommendations.items.reorder');

    // 아티스트 검색(JSON)
    Route::get('artist-search', [RecommendationController::class, 'artistSearch'])
        ->name('artist_search');

    /*
    |------------------------------------
    | 추천안 공유/토큰 관리
    |------------------------------------
    */
    Route::post('/intakes/{intake}/recommendations/share',   RecommendationShareController::class)
        ->name('recommendations.share');

    Route::post('/intakes/{intake}/recommendations/rotate',  [RecommendationShareController::class, 'rotate'])
        ->name('recommendations.rotate');

    Route::get('/intakes/{intake}/recommendations/share-log.json', [RecommendationShareController::class, 'history'])
        ->name('recommendations.share_log');

    Route::post('/intakes/{intake}/recommendations/revoke',  [RecommendationShareController::class, 'revoke'])
        ->name('recommendations.revoke');

    /*
    |------------------------------------
    | 아티스트/분류 CRUD
    |------------------------------------
    */
    // 분류(분야/장르/태그) 관리
    Route::get('taxonomies', [TaxonomyController::class, 'index'])->name('taxonomies.index');
    Route::post('taxonomies', [TaxonomyController::class, 'store'])->name('taxonomies.store');
    Route::put('taxonomies/{type}/{id}', [TaxonomyController::class, 'update'])->name('taxonomies.update');
    Route::delete('taxonomies/{type}/{id}', [TaxonomyController::class, 'destroy'])->name('taxonomies.destroy');

    // 아티스트 CRUD
    Route::resource('artists', ArtistController::class);
    Route::resource('success-stories', SuccessStoryController::class)->except(['show']);
});
