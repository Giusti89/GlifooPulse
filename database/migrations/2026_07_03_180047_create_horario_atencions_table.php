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
        Schema::create('horario_atencions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')->constrained('spots')->onDelete('cascade');
            // 1 = Lunes, 7 = Domingo
            $table->unsignedTinyInteger('dia');
            // 🔹 Primer Turno (Mañana)
            $table->time('apertura')->nullable();
            $table->time('cierre')->nullable();

            // 🔹 Segundo Turno (Tarde / Noche)
            $table->time('apertura_2')->nullable();
            $table->time('cierre_2')->nullable();

            $table->boolean('esta_cerrado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_atencions');
    }
};
