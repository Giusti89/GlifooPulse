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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')
                ->constrained('spots')
                ->cascadeOnDelete();

            $table->string('nombre');
            $table->string('slug', 191); 
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0); 
            $table->timestamps();

            
            $table->index('spot_id');
           
            $table->unique(['spot_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
