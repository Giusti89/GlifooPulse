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
        Schema::table('spots', function (Blueprint $table) {
            $table->unsignedBigInteger('contador')->default(0)->after('estado');
        });
         DB::statement('
            UPDATE spots 
            SET contador= (
                SELECT COUNT(*) 
                FROM visits 
                WHERE visits.spot_id = spots.id
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
             $table->dropColumn('contador');
        });
    }
};
