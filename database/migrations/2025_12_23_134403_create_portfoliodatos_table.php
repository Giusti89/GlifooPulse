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
        Schema::create('portfoliodatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')
                ->constrained()
                ->onDelete('cascade');
            $table->text('implicacion')->nullable();
            $table->json('tecnologias')->nullable();
            $table->string('cliente')->nullable();
            $table->string('enlace_proyecto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfoliodatos');
    }
};
