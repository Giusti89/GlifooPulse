<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained('spots')->cascadeOnDelete();
            $table->string('imagen')->nullable();            
            $table->string('resumen', 500);
            $table->string('titulo', 255);
            $table->string('slug');
            $table->enum('status', ['publicado', 'borrador'])->default('borrador');
            $table->boolean('exclusivo')->default(false);
            $table->timestamps();
            $table->unique(['spot_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
