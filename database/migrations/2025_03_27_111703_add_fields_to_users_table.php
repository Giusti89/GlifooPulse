<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('rol_id')
                ->nullable()
                ->default(2)
                ->constrained('rols');
        });
        DB::table('users')->insert([
            [
                'name' => 'Giusti',
                'email' => 'giusti.17@hotmail.com',
                'lastname' => 'Villarroel',
                'phone' => '+59172501311',
                'password' => Hash::make('17041989'),
                'estado_id' => '1',
                'rol_id' => '1',
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['rol_id']);
                $table->dropColumn('rol_id');
            });
        });
    }
};
