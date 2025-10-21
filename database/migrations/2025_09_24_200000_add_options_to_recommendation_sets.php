<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recommendation_sets', function (Blueprint $table) {
            if (!Schema::hasColumn('recommendation_sets', 'options')) {
                $table->json('options')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('recommendation_sets', function (Blueprint $table) {
            if (Schema::hasColumn('recommendation_sets', 'options')) {
                $table->dropColumn('options');
            }
        });
    }
};
