<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            // Already created by another migration; ensure index exists and bail out
            Schema::table('payments', function (Blueprint $table) {
                try { $table->index(['user_id', 'created_at']); } catch (\Throwable $e) { /* ignore */ }
            });
            return;
        }
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('amount');
            $table->string('currency', 8)->default('KRW');
            $table->string('status', 32)->default('pending'); // pending, paid, canceled, refunded
            $table->string('description')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
