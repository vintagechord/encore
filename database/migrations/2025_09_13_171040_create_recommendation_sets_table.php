<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recommendation_sets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('intake_request_id')->constrained('intake_requests')->cascadeOnDelete();
            $t->string('label');               // "A) 단독 구성" 등
            $t->json('items');                 // [{artist_id,name,est_fee}]
            $t->unsignedInteger('total_cost')->nullable();
            $t->text('rationale')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('recommendation_sets');
    }
};
