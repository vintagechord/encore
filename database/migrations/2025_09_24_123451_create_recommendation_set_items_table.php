<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('recommendation_set_items')) return;

        Schema::create('recommendation_set_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recommendation_set_id');
            $table->unsignedBigInteger('artist_id')->nullable();
            $table->integer('rank')->default(0);
            $table->boolean('fixed')->default(false);
            $table->boolean('excluded')->default(false);
            $table->unsignedBigInteger('quoted_min')->nullable();
            $table->unsignedBigInteger('quoted_max')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index('recommendation_set_id');
            $table->index('artist_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_set_items');
    }
};
