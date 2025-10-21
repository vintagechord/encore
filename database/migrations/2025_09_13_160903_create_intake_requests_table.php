<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('intake_requests', function (Blueprint $t) {
            $t->id();
            $t->string('contact_name');
            $t->string('contact_email');
            $t->string('contact_phone')->nullable();
            $t->string('org_name')->nullable();

            $t->date('event_date')->nullable();
            $t->boolean('date_flexible')->default(false);
            $t->string('city')->nullable();
            $t->enum('venue_type', ['school', 'festival', 'club', 'brand', 'wedding', 'conference', 'other'])->nullable();
            $t->enum('indoor_outdoor', ['indoor', 'outdoor'])->nullable();
            $t->unsignedInteger('audience_size')->nullable();

            $t->string('currency', 3)->default('KRW');
            $t->unsignedInteger('budget_min')->nullable();
            $t->unsignedInteger('budget_max')->nullable();
            $t->json('genres')->nullable();
            $t->json('moods')->nullable();
            $t->string('audience_age')->nullable();
            $t->enum('performance_type', ['live_band', 'solo', 'duo', 'dj', 'acoustic', 'talkshow', 'other'])->nullable();
            $t->unsignedInteger('set_duration')->nullable();
            $t->unsignedTinyInteger('sets_count')->default(1);
            $t->json('equipment_available')->nullable();

            $t->string('requested_artist_name')->nullable();
            $t->text('notes')->nullable();

            $t->enum('status', ['new', 'processing', 'recommended', 'closed'])->default('new');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_requests');
    }
};
