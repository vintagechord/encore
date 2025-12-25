<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('intake_requests', 'mc_roles')) {
            Schema::table('intake_requests', function (Blueprint $table) {
                // MC 세부 역할(배열) – JSON/text
                $table->json('mc_roles')->nullable()->after('dance_genres');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('intake_requests', 'mc_roles')) {
            Schema::table('intake_requests', function (Blueprint $table) {
                $table->dropColumn('mc_roles');
            });
        }
    }
};
