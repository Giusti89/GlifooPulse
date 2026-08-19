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

            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->string('status')->default('draft'); // draft, published
            $table->boolean('is_exclusive')->default(false);
            $table->string('youtube_url')->nullable(); 

            $table->timestamps();

            // Un slug único por Spot evita colisiones de URL
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
