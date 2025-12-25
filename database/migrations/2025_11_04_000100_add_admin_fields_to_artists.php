<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            if (!Schema::hasColumn('artists', 'discipline_id')) {
                $table->foreignId('discipline_id')->nullable()->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('artists', 'legal_name')) {
                $table->string('legal_name')->nullable();
            }
            if (!Schema::hasColumn('artists', 'fame_score')) {
                $table->unsignedTinyInteger('fame_score')->nullable();
            }
            if (!Schema::hasColumn('artists', 'external_links')) {
                $table->json('external_links')->nullable();
            }
            if (!Schema::hasColumn('artists', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('artists', 'image_path')) {
                $table->string('image_path')->nullable();
            }
            if (!Schema::hasColumn('artists', 'image_url')) {
                $table->string('image_url')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
            if (Schema::hasColumn('artists', 'image_url')) $table->dropColumn('image_url');
            if (Schema::hasColumn('artists', 'image_path')) $table->dropColumn('image_path');
            if (Schema::hasColumn('artists', 'bio')) $table->dropColumn('bio');
            if (Schema::hasColumn('artists', 'external_links')) $table->dropColumn('external_links');
            if (Schema::hasColumn('artists', 'fame_score')) $table->dropColumn('fame_score');
            if (Schema::hasColumn('artists', 'legal_name')) $table->dropColumn('legal_name');
            if (Schema::hasColumn('artists', 'discipline_id')) $table->dropConstrainedForeignId('discipline_id');
        });
    }
};

