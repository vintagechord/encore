<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('섭외 확정');
            $table->string('title');            // 아티스트/프로젝트 명
            $table->string('role')->nullable(); // 선택: 연예인/밴드 등
            $table->string('event_name')->nullable();
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->string('thumbnail_path')->nullable(); // 저장된 업로드 경로나 URL
            $table->text('summary')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};
