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
        Schema::create('seos', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('spot_id')
                ->constrained('spots')
                ->onDelete('cascade')
                ->nullable();

            $table->string('logo')->nullable(); // ruta del logo optimizado para SEO
            $table->string('descripcion', 500)->nullable(); // descripción larga opcional
            $table->string('seo_title', 255)->nullable(); // título SEO
            $table->string('seo_descripcion', 500)->nullable(); // meta description
            $table->string('seo_keyword', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};
