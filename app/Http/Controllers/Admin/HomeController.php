<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'intakes' => $this->safeCount(\App\Models\IntakeRequest::class),
            'recommendation_sets' => $this->safeCount(\App\Models\RecommendationSet::class),
            'artists' => $this->safeCount(\App\Models\Artist::class),
            'payments' => class_exists(\App\Models\Payment::class) ? $this->safeCount(\App\Models\Payment::class) : 0,
        ];

        return view('admin.home', compact('stats'));
    }

    protected function safeCount(string $model): int
    {
        try {
            $table = (new $model)->getTable();
            if (!Schema::hasTable($table)) return 0;
            return $model::query()->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}

