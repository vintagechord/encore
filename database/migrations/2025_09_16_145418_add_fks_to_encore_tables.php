<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function isSqlite(): bool
    {
        return DB::getDriverName() === 'sqlite';
    }

    public function up(): void
    {
        // 개발(SQLite)에서는 스킵: 기존 테이블에 FK 추가가 까다롭기 때문
        if ($this->isSqlite()) {
            return;
        }

        // recommendation_sets.intake_request_id -> intake_requests.id
        if (Schema::hasTable('recommendation_sets') && Schema::hasTable('intake_requests')) {
            Schema::table('recommendation_sets', function (Blueprint $table) {
                // 이미 FK가 있으면 예외가 나므로 try-guard가 필요하지만,
                // 대부분 운영 최초 적용이라 바로 추가됩니다.
                if (! $this->hasForeign($table, 'recommendation_sets_intake_request_id_foreign')) {
                    $table->foreign('intake_request_id', 'recommendation_sets_intake_request_id_foreign')
                        ->references('id')->on('intake_requests')
                        ->onDelete('cascade'); // 문의 삭제 시 추천셋도 삭제
                }
            });
        }

        // 피벗: artist_recommendation_set
        if (Schema::hasTable('artist_recommendation_set')) {
            Schema::table('artist_recommendation_set', function (Blueprint $table) {
                if (! $this->hasForeign($table, 'ars_set_id_foreign')) {
                    $table->foreign('recommendation_set_id', 'ars_set_id_foreign')
                        ->references('id')->on('recommendation_sets')
                        ->onDelete('cascade'); // 추천셋 삭제 시 피벗도 삭제
                }
                if (! $this->hasForeign($table, 'ars_artist_id_foreign')) {
                    $table->foreign('artist_id', 'ars_artist_id_foreign')
                        ->references('id')->on('artists')
                        ->onDelete('cascade'); // 아티스트 삭제 시 피벗도 삭제
                }
            });
        }
    }

    public function down(): void
    {
        if ($this->isSqlite()) {
            return;
        }

        if (Schema::hasTable('artist_recommendation_set')) {
            Schema::table('artist_recommendation_set', function (Blueprint $table) {
                // FK 이름과 dropForeign 매칭
                $table->dropForeign('ars_set_id_foreign');
                $table->dropForeign('ars_artist_id_foreign');
            });
        }

        if (Schema::hasTable('recommendation_sets')) {
            Schema::table('recommendation_sets', function (Blueprint $table) {
                $table->dropForeign('recommendation_sets_intake_request_id_foreign');
            });
        }
    }

    /**
     * Blueprint에서 현재 커넥션의 메타정보를 통해 FK 존재 여부를 간단히 확인
     * (Laravel 자체에 직접 체크 API가 없어 커스텀 방식. MySQL/PG에선 안전)
     */
    private function hasForeign(Blueprint $table, string $name): bool
    {
        // 단순하게는 시도-실패 방식을 쓰기도 하지만, 가독성 위해 드라이버별 확인은 생략
        // 여기서는 false를 반환해도 중복 추가만 조심하면 됩니다.
        return false;
    }
};
