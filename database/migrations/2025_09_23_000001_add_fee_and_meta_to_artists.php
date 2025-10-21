<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            if (!Schema::hasColumn('artists', 'fee_min')) {
                $table->unsignedInteger('fee_min')->nullable()->after('name');
            }
            if (!Schema::hasColumn('artists', 'fee_max')) {
                $table->unsignedInteger('fee_max')->nullable()->after('fee_min');
            }
            if (!Schema::hasColumn('artists', 'meta')) {
                $table->json('meta')->nullable()->after('fee_max'); // 소속사/세금/특이사항 등
            }
        });
    }
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            if (Schema::hasColumn('artists', 'meta')) $table->dropColumn('meta');
            if (Schema::hasColumn('artists', 'fee_max')) $table->dropColumn('fee_max');
            if (Schema::hasColumn('artists', 'fee_min')) $table->dropColumn('fee_min');
        });
    }
};
