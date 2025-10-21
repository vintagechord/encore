<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recommendation_sets', function (Blueprint $table) {
            // SQLite 호환: 위치 지정(after) 사용 안 함
            $table->string('public_token', 64)->nullable()->unique();
            $table->timestamp('sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('recommendation_sets', function (Blueprint $table) {
            $table->dropColumn(['public_token', 'sent_at']);
        });
    }
};
