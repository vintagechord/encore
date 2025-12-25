<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('intake_requests', 'biz_cert_path')) {
            Schema::table('intake_requests', function (Blueprint $table) {
                $table->string('biz_cert_path')->nullable()->after('org_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('intake_requests', 'biz_cert_path')) {
            Schema::table('intake_requests', function (Blueprint $table) {
                $table->dropColumn('biz_cert_path');
            });
        }
    }
};

