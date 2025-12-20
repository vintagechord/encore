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
            if (! $this->foreignExists('recommendation_sets', 'recommendation_sets_intake_request_id_foreign')) {
                Schema::table('recommendation_sets', function (Blueprint $table) {
                    $table->foreign('intake_request_id', 'recommendation_sets_intake_request_id_foreign')
                        ->references('id')->on('intake_requests')
                        ->onDelete('cascade'); // 문의 삭제 시 추천셋도 삭제
                });
            }
        }

        // 피벗: artist_recommendation_set
        if (Schema::hasTable('artist_recommendation_set')) {
            if (! $this->foreignExists('artist_recommendation_set', 'ars_set_id_foreign')) {
                Schema::table('artist_recommendation_set', function (Blueprint $table) {
                    $table->foreign('recommendation_set_id', 'ars_set_id_foreign')
                        ->references('id')->on('recommendation_sets')
                        ->onDelete('cascade'); // 추천셋 삭제 시 피벗도 삭제
                });
            }
            if (! $this->foreignExists('artist_recommendation_set', 'ars_artist_id_foreign')) {
                Schema::table('artist_recommendation_set', function (Blueprint $table) {
                    $table->foreign('artist_id', 'ars_artist_id_foreign')
                        ->references('id')->on('artists')
                        ->onDelete('cascade'); // 아티스트 삭제 시 피벗도 삭제
                });
            }
        }
    }

    public function down(): void
    {
        if ($this->isSqlite()) {
            return;
        }

        if (Schema::hasTable('artist_recommendation_set')) {
            if ($this->foreignExists('artist_recommendation_set', 'ars_set_id_foreign')) {
                Schema::table('artist_recommendation_set', function (Blueprint $table) {
                    $table->dropForeign('ars_set_id_foreign');
                });
            }
            if ($this->foreignExists('artist_recommendation_set', 'ars_artist_id_foreign')) {
                Schema::table('artist_recommendation_set', function (Blueprint $table) {
                    $table->dropForeign('ars_artist_id_foreign');
                });
            }
        }

        if (Schema::hasTable('recommendation_sets')) {
            if ($this->foreignExists('recommendation_sets', 'recommendation_sets_intake_request_id_foreign')) {
                Schema::table('recommendation_sets', function (Blueprint $table) {
                    $table->dropForeign('recommendation_sets_intake_request_id_foreign');
                });
            }
        }
    }

    /**
     * Blueprint에서 현재 커넥션의 메타정보를 통해 FK 존재 여부를 간단히 확인
     * (Laravel 자체에 직접 체크 API가 없어 커스텀 방식. MySQL/PG에선 안전)
     */
    private function foreignExists(string $table, string $name): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            $row = DB::selectOne(
                'select 1 from pg_constraint c join pg_class t on t.oid = c.conrelid where c.conname = ? and t.relname = ? limit 1',
                [$name, $table]
            );
            return $row !== null;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $row = DB::selectOne(
                "select 1 from information_schema.TABLE_CONSTRAINTS where CONSTRAINT_SCHEMA = database() and CONSTRAINT_NAME = ? and TABLE_NAME = ? and CONSTRAINT_TYPE = 'FOREIGN KEY' limit 1",
                [$name, $table]
            );
            return $row !== null;
        }

        return false;
    }
};
