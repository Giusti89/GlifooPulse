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
        Schema::table('socials', function (Blueprint $table) {
            $table->foreignId('tipored_id')
            ->nullable()
            ->constrained('tiporeds')
            ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('socials', function (Blueprint $table) {
            $table->dropForeign(['tipored_id']);
            $table->dropColumn('tipored_id');
        });
    }
};
