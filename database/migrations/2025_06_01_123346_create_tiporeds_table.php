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
        Schema::create('tiporeds', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });
         DB::table('tiporeds')->insert([
            ['nombre' => 'Red Social'],
            ['nombre' => 'Otra Red'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiporeds');
    }
};
