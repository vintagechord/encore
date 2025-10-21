<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('artist_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
            $table->string('currency', 3)->default('KRW');         // KRW, USD...
            $table->unsignedBigInteger('min_fee')->nullable();     // 최소 견적
            $table->unsignedBigInteger('max_fee')->nullable();     // 최대 견적
            $table->enum('unit', ['appearance', 'set', 'hour', 'day'])->default('appearance');
            $table->string('region_code', 8)->nullable();          // KR, US-CA 등 (선택)
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['artist_id', 'currency', 'region_code']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('artist_fees');
    }
};
