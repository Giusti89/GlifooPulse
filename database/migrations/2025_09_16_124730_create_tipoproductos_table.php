<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipoproductos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('detalle')->nullable();
            $table->timestamps();
        });
         DB::table('tipoproductos')->insert([
            [
                'nombre' => 'Landing page',
                'detalle' => 'Servicio de landing page',
            ],
            [
                'nombre' => 'Catalogo',
                'detalle' => 'Servicio muestra de productos',
            ],

        ]);
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoproductos');
    }
};
