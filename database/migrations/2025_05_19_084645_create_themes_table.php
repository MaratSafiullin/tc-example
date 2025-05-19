<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');

            $table->unique(['set_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
