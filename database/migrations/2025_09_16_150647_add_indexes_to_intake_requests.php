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
        $table = 'intake_requests';
        if (!Schema::hasTable($table)) return;

        Schema::table($table, function (Blueprint $t) use ($table) {
            // 목록 정렬/최근 50건 조회 최적화
            $idxCreated = 'intake_created_at_index';
            if (!$this->indexExists($table, $idxCreated)) {
                $t->index('created_at', $idxCreated);
            }

            // 이메일로 검색/필터 대비
            $idxEmail = 'intake_contact_email_index';
            if (Schema::hasColumn($table, 'contact_email') && !$this->indexExists($table, $idxEmail)) {
                $t->index('contact_email', $idxEmail);
            }
        });
    }

    public function down(): void
    {
        $table = 'intake_requests';
        if (!Schema::hasTable($table)) return;

        Schema::table($table, function (Blueprint $t) {
            $t->dropIndex('intake_created_at_index');
            $t->dropIndex('intake_contact_email_index');
        });
    }
};
