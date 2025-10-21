<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('intake_requests', function (Blueprint $table) {
            // 행사 기간(단일이면 start=end)
            if (!Schema::hasColumn('intake_requests', 'event_start')) {
                $table->date('event_start')->nullable()->after('event_date');
            }
            if (!Schema::hasColumn('intake_requests', 'event_end')) {
                $table->date('event_end')->nullable()->after('event_start');
            }

            // 복수 분야/세부 선택(배열 저장용)
            if (!Schema::hasColumn('intake_requests', 'performance_categories')) {
                $table->json('performance_categories')->nullable()->after('budget_max');
            }
            if (!Schema::hasColumn('intake_requests', 'music_genres')) {
                $table->json('music_genres')->nullable()->after('performance_categories');
            }
            if (!Schema::hasColumn('intake_requests', 'dance_genres')) {
                $table->json('dance_genres')->nullable()->after('music_genres');
            }
            if (!Schema::hasColumn('intake_requests', 'mc_roles')) {
                $table->json('mc_roles')->nullable()->after('dance_genres');
            }
        });
    }

    public function down(): void
    {
        Schema::table('intake_requests', function (Blueprint $table) {
            // 존재할 때만 제거 (roll-back 안전)
            if (Schema::hasColumn('intake_requests', 'mc_roles')) {
                $table->dropColumn('mc_roles');
            }
            if (Schema::hasColumn('intake_requests', 'dance_genres')) {
                $table->dropColumn('dance_genres');
            }
            if (Schema::hasColumn('intake_requests', 'music_genres')) {
                $table->dropColumn('music_genres');
            }
            if (Schema::hasColumn('intake_requests', 'performance_categories')) {
                $table->dropColumn('performance_categories');
            }
            if (Schema::hasColumn('intake_requests', 'event_end')) {
                $table->dropColumn('event_end');
            }
            if (Schema::hasColumn('intake_requests', 'event_start')) {
                $table->dropColumn('event_start');
            }
        });
    }
};
