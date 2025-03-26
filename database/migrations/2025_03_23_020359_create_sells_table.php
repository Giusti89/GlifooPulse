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
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 10, 2)->default(0.00);
            $table->decimal('pago', 10, 2)->default(0.00)->nullable();
            $table->date('fecha')->nullable();
            $table->timestamps();

            $table->foreignId('suscripcion_id')
            ->constrained('suscripcions')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells');
    }
};
