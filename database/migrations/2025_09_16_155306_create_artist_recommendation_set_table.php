<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('artist_recommendation_set', function (Blueprint $table) {
            $table->id();

            // FK
            $table->foreignId('recommendation_set_id')
                ->constrained('recommendation_sets')
                ->cascadeOnDelete();

            $table->foreignId('artist_id')
                ->constrained('artists')
                ->cascadeOnDelete();

            // 메타
            $table->unsignedSmallInteger('rank')->nullable()->index();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('reason')->nullable();

            $table->timestamps();

            // 동일 추천셋 내 동일 아티스트 중복 방지
            $table->unique(['recommendation_set_id', 'artist_id'], 'ars_unique');

            // 추천셋별 정렬 최적화
            $table->index(['recommendation_set_id', 'rank'], 'ars_set_rank_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_recommendation_set');
    }
};
