<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_token_events', function (Blueprint $table) {
            $table->id();

            // 어떤 추천셋(recommendation_sets)에 대한 이벤트인지
            $table->unsignedBigInteger('recommendation_set_id')->index();

            // 당시 사용되던 공개 토큰(회전/회수 전후 토큰을 이벤트로 남긴다)
            $table->string('token')->index();

            // 이벤트 종류: issued / rotated / revoked
            $table->string('event_type', 20)->index();

            // 선택: 사람이 남기는 사유/메모
            $table->string('reason')->nullable();

            // 선택: 누가 실행했는지(관리자) 추적하고 싶다면 user_id 저장
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->timestamps();

            // FK (on delete cascade)
            $table->foreign('recommendation_set_id')
                ->references('id')
                ->on('recommendation_sets')
                ->onDelete('cascade');
        });

        // 조회 자주 쓰는 조합 보조 인덱스
        Schema::table('share_token_events', function (Blueprint $table) {
            $table->index(['token', 'event_type', 'created_at'], 'ste_token_type_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_token_events');
    }
};
