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
            $table->string('colsecond', 20)->nullable()->after('background');
            $table->string('phone')->unique()->nullable()->after('colsecond');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contenidos', function (Blueprint $table) {
           $table->dropColumn([
                'colsecond',
                'phone',
            ]);
        });
    }
};
