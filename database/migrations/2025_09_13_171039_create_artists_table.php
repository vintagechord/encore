<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $t) {
            $t->id();
            $t->string('stage_name');
            $t->unsignedInteger('min_fee')->nullable();
            $t->unsignedInteger('max_fee')->nullable();
            $t->json('genres')->nullable();    // ["hiphop","indie"]
            $t->json('moods')->nullable();     // ["chill","hype"]
            $t->json('formats')->nullable();   // ["dj","solo","live_band"]
            $t->string('home_city')->nullable();
            $t->boolean('active')->default(true);
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
