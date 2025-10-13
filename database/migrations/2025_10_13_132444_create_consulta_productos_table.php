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
        Schema::create('consulta_productos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('nombre')->nullable();
            $table->string('telefono')->nullable();
            
            $table->text('mensaje'); 

            $table->string('ip_usuario', 45)->nullable();
            
            $table->timestamp('fecha_consulta')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulta_productos');
    }
};
