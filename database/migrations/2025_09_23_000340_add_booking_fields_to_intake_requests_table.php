<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('intake_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('intake_requests', 'budget_min')) {
                $table->unsignedBigInteger('budget_min')->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('intake_requests', 'budget_max')) {
                $table->unsignedBigInteger('budget_max')->nullable()->after('budget_min');
            }
            if (!Schema::hasColumn('intake_requests', 'event_start')) {
                $table->date('event_start')->nullable()->after('budget_max');
            }
            if (!Schema::hasColumn('intake_requests', 'event_end')) {
                $table->date('event_end')->nullable()->after('event_start');
            }
            if (!Schema::hasColumn('intake_requests', 'category')) {
                $table->string('category', 32)->nullable()->after('event_end');
            }
            if (!Schema::hasColumn('intake_requests', 'genre_counts')) {
                $table->json('genre_counts')->nullable()->after('category');
            }
        });
    }

    public function down(): void
    {
        Schema::table('intake_requests', function (Blueprint $table) {
            // 존재할 때만 드롭(개별 체크)
            foreach (['genre_counts', 'category', 'event_end', 'event_start', 'budget_max', 'budget_min'] as $col) {
                if (Schema::hasColumn('intake_requests', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
