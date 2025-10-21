<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // 현재 커넥션에서 인덱스 존재 여부 검사
    private function indexExists(string $table, string $indexName): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // PRAGMA index_list('<table>') -> rows with ->name
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
            $rows = DB::select("SELECT 1 FROM pg_indexes WHERE tablename = ? AND indexname = ?", [$table, $indexName]);
            return count($rows) > 0;
        }

        // 기타 드라이버는 시도하지 않음
        return false;
    }

    public function up(): void
    {
        $table = 'recommendation_sets';

        Schema::table($table, function (Blueprint $t) use ($table) {
            // 인덱스 이름들
            $uniqToken = 'recommendation_sets_public_token_unique';
            $idxIntake = 'recommendation_sets_intake_request_id_index';
            $idxSent   = 'recommendation_sets_sent_at_index';
            $idxCreated = 'recommendation_sets_created_at_index';

            // 이미 있으면 생성을 건너뜀
            if (!$this->indexExists($table, $uniqToken)) {
                $t->unique('public_token', $uniqToken);
            }
            if (!$this->indexExists($table, $idxIntake)) {
                $t->index('intake_request_id', $idxIntake);
            }
            if (!$this->indexExists($table, $idxSent)) {
                $t->index('sent_at', $idxSent);
            }
            if (!$this->indexExists($table, $idxCreated)) {
                $t->index('created_at', $idxCreated);
            }
        });
    }

    public function down(): void
    {
        $table = 'recommendation_sets';

        Schema::table($table, function (Blueprint $t) use ($table) {
            $uniqToken = 'recommendation_sets_public_token_unique';
            $idxIntake = 'recommendation_sets_intake_request_id_index';
            $idxSent   = 'recommendation_sets_sent_at_index';
            $idxCreated = 'recommendation_sets_created_at_index';

            // 존재할 때만 삭제 (SQLite에서도 안전)
            if ($this->indexExists($table, $uniqToken)) {
                $t->dropUnique($uniqToken);
            }
            if ($this->indexExists($table, $idxIntake)) {
                $t->dropIndex($idxIntake);
            }
            if ($this->indexExists($table, $idxSent)) {
                $t->dropIndex($idxSent);
            }
            if ($this->indexExists($table, $idxCreated)) {
                $t->dropIndex($idxCreated);
            }
        });
    }
};
