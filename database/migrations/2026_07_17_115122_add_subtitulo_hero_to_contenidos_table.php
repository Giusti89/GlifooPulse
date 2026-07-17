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
        Schema::table('contenidos', function (Blueprint $table) {
            $table->string('subtitulo_hero')->nullable()->after('texto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contenidos', function (Blueprint $table) {
             $table->dropColumn('subtitulo_hero');
        });
    }
};
