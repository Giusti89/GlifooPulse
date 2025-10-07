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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();


            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->onDelete('cascade');

            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->default(0);


            $table->string('slug');
            $table->unique(['categoria_id', 'slug']);


            $table->enum('estado', ['Disponible', 'Agotado', 'No disponible', 'Proximamente'])
                ->default('Disponible');


            $table->unsignedInteger('orden')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
