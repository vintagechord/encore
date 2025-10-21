<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OptionController;

Route::post('/options', [OptionController::class, 'generate']);       // 공개 사용 가능(레이트리밋 권장)
Route::post('/options/regenerate', [OptionController::class, 'regenerate']);

Route::middleware('auth')->group(function () {
    Route::post('/options/commit', [OptionController::class, 'commit']); // 선택 옵션을 RecommendationSet으로 커밋
});
