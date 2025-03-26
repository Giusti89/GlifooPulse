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
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->decimal('precio', 10, 2)->default(0.00);
            $table->integer('duaracion')->default(1);
            $table->text('image_url');
            $table->text('enlace');
            $table->boolean('estado')->default(0);
            $table->text('marco')->nullable();

            $table->foreignId('landing_id')
                ->constrained('landings')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paquetes');
    }
};
