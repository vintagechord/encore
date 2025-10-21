<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('artist_unavailabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id')->index();
            $table->date('starts_on'); // 불가 시작 (일 단위)
            $table->date('ends_on');   // 불가 끝   (일 단위)
            $table->string('reason')->nullable(); // 투어/개인사유 등
            $table->timestamps();

            $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('artist_unavailabilities');
    }
};
