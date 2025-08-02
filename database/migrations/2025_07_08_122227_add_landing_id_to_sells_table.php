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
        Schema::table('sells', function (Blueprint $table) {
            $table->unsignedBigInteger('landing_id')->nullable()->after('suscripcion_id');
            $table->foreign('landing_id')->references('id')->on('landings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sells', function (Blueprint $table) {
            $table->dropForeign(['landing_id']);
            $table->dropColumn('landing_id');
        });
    }
};
