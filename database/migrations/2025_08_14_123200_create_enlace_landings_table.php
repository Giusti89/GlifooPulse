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
        Schema::create('enlace_landings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landing_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('enlace_id')
                ->constrained()
                ->onDelete('cascade');
            $table->unique(['landing_id', 'enlace_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enlace_landings');
    }
};
