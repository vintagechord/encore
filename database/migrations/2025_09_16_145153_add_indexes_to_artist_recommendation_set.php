<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $table, string $indexName): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $rows = DB::select("PRAGMA index_list('{$table}')");
            foreach ($rows as $row) {
                if (($row->name ?? null) === $indexName) return true;
            }
            return false;
        }

        if ($driver === 'mysql') {
            $rows = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return count($rows) > 0;
        }

        if ($driver === 'pgsql') {
            $rows = DB::select(
                "SELECT 1 FROM pg_indexes WHERE tablename = ? AND indexname = ?",
                [$table, $indexName]
            );
            return count($rows) > 0;
        }

        return false;
    }

    public function up(): void
    {
        // ← 피벗 테이블 이름이 다르면 이 한 줄만 바꿔주세요.
        $table = 'artist_recommendation_set';

        if (!Schema::hasTable($table)) {
            // 피벗을 아직 안 쓰는 프로젝트면 그냥 건너뜀
            return;
        }

        Schema::table($table, function (Blueprint $t) use ($table) {
            // 인덱스 이름들(명시적으로 지정: 생성/삭제 안전)
            $idxSetId   = 'ars_set_id_index';
            $idxArtistId = 'ars_artist_id_index';
            $uniqPair   = 'ars_unique_set_artist';
            $idxSetRank = 'ars_set_rank_index';

            // 기본: FK 단건 검색/조인 최적화
            if (!$this->indexExists($table, $idxSetId))    $t->index('recommendation_set_id', $idxSetId);
            if (!$this->indexExists($table, $idxArtistId)) $t->index('artist_id', $idxArtistId);

            // 중복 방지: 같은 세트에 동일 아티스트가 두 번 들어가는 것 차단
            if (!$this->indexExists($table, $uniqPair)) {
                $t->unique(['recommendation_set_id', 'artist_id'], $uniqPair);
            }

            // rank 컬럼이 있을 때 순위 정렬/조회 최적화
            if (Schema::hasColumn($table, 'rank') && !$this->indexExists($table, $idxSetRank)) {
                $t->index(['recommendation_set_id', 'rank'], $idxSetRank);
            }
        });
    }

    public function down(): void
    {
        $table = 'artist_recommendation_set';
        if (!Schema::hasTable($table)) return;

        Schema::table($table, function (Blueprint $t) use ($table) {
            $idxSetId   = 'ars_set_id_index';
            $idxArtistId = 'ars_artist_id_index';
            $uniqPair   = 'ars_unique_set_artist';
            $idxSetRank = 'ars_set_rank_index';

            if ($this->indexExists($table, $uniqPair))   $t->dropUnique($uniqPair);
            if ($this->indexExists($table, $idxSetRank)) $t->dropIndex($idxSetRank);
            if ($this->indexExists($table, $idxSetId))   $t->dropIndex($idxSetId);
            if ($this->indexExists($table, $idxArtistId)) $t->dropIndex($idxArtistId);
        });
    }
};
