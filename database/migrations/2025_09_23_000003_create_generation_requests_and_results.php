<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('generation_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intake_request_id')->nullable()->index();
            $table->unsignedInteger('budget_total')->nullable();
            $table->date('event_start')->nullable();
            $table->date('event_end')->nullable();
            $table->json('genre_counts'); // {"pop":2,"dance":1} 형태
            $table->json('locked_artist_ids')->nullable();   // [1,5]
            $table->json('excluded_artist_ids')->nullable(); // [3,7]
            $table->string('seed', 64)->nullable();
            $table->unsignedInteger('option_count')->default(3);
            $table->string('client_ip', 64)->nullable();
            $table->timestamps();
        });

        Schema::create('generation_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('generation_request_id')->index();
            $table->unsignedInteger('option_index'); // 0..N-1
            $table->json('artist_ids');     // [10,22,35]
            $table->unsignedInteger('subtotal')->nullable();
            $table->json('score_breakdown')->nullable(); // {"fit":0.82,"diversity":0.6}
            $table->timestamps();

            $table->foreign('generation_request_id')
                ->references('id')->on('generation_requests')
                ->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('generation_results');
        Schema::dropIfExists('generation_requests');
    }
};
