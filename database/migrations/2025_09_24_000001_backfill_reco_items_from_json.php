<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('recommendation_set_items')) return;
        if (!Schema::hasTable('recommendation_sets')) return;

        $sets = DB::table('recommendation_sets')
            ->select('id', 'items')
            ->whereNotNull('items')
            ->get();

        foreach ($sets as $s) {
            $items = is_string($s->items) ? json_decode($s->items, true) : $s->items;
            if (!is_array($items)) continue;

            $rank = 1;
            foreach ($items as $it) {
                DB::table('recommendation_set_items')->insert([
                    'recommendation_set_id' => $s->id,
                    'artist_id'  => $it['artist_id'] ?? null,
                    'rank'       => $it['rank'] ?? $rank,
                    'fixed'      => (bool)($it['fixed'] ?? false),
                    'excluded'   => (bool)($it['excluded'] ?? false),
                    'quoted_min' => $it['quoted_min'] ?? $it['min'] ?? null,
                    'quoted_max' => $it['quoted_max'] ?? $it['max'] ?? null,
                    'meta'       => json_encode([
                        'score'  => $it['score']  ?? null,
                        'reason' => $it['reason'] ?? null,
                        'currency' => $it['currency'] ?? 'KRW',
                        'unit' => $it['unit'] ?? 'appearance',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $rank++;
            }
        }
    }

    public function down(): void
    {
        // 백필 롤백은 파괴적이므로 비워둠
    }
};
