<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('quotes')) return;
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intake_request_id')->constrained('intake_requests')->cascadeOnDelete();
            $table->string('title');
            $table->text('body')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->string('currency', 10)->default('KRW');
            $table->string('status', 30)->default('draft'); // draft|sent|accepted|rejected
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['intake_request_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};

