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
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique()->onDelete('cascade');
            $table->timestamps();
        });
        DB::table('estados')->insert([
            ['nombre' => 'activo'],
            ['nombre' => 'inactivo'],
            ['nombre' => 'suspedido'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};
