<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->string('external_id')->nullable();
            $table->string('name');
            $table->string('status');
            $table->string('context_type');
            $table->text('context');
            $table->string('callback_url')->nullable();
            $table->unsignedTinyInteger('callback_tries')->default(0);
            $table->timestamp('do_callback_after')->nullable();

            $table->timestamps();

            $table->unique(['owner_id', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};
