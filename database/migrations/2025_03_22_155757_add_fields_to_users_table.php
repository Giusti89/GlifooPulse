<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('estado_id')
                ->nullable()
                ->default(2)
                ->constrained('estados')
                ->onDelete('cascade');
        });
        DB::table('users')->insert([
            [
                'name' => 'Giusti',
                'email' => 'giusti.17@hotmail.com',
                'lastname' => 'Villarroel',
                'phone' => '+59172501311',
                'password' => Hash::make('17041989'),
                'estado_id' => '1',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropColumn('estado_id');
        });
    }
};
