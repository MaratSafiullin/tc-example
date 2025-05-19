<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('theme_text', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('text_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('sentiment', 50);

            $table->unique(['theme_id', 'text_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_text');
    }
};
