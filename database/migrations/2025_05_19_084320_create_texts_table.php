<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('texts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_id')->constrained('sets')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('external_id')->nullable();
            $table->string('text', 2000);
            $table->string('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('texts');
    }
};
