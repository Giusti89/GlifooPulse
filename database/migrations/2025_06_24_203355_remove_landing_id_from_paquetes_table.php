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
        Schema::table('paquetes', function (Blueprint $table) {
            if (Schema::hasColumn('paquetes', 'landing_id')) {
                $table->dropForeign(['landing_id']);
                $table->dropColumn('landing_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
             $table->unsignedBigInteger('landing_id')->nullable()->after('macro_text');

            $table->foreign('landing_id')->references('id')->on('landings')->onDelete('set null');
        });
    }
};
