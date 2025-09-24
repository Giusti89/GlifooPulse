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
            $table->integer('max_productos')->nullable()->after('descripcion');
            $table->integer('max_redes_sociales')->nullable()->after('max_productos');
            $table->enum('tipo_estadisticas', ['ninguna', 'basica', 'avanzada'])
                ->default('ninguna')
                ->after('max_redes_sociales');
            $table->integer('max_imagenes_producto')->nullable()->after('tipo_estadisticas');
            $table->integer('max_categorias')->nullable()->after('max_imagenes_producto');
            $table->enum('seo_level', ['basico', 'medio', 'completo'])
                ->default('basico')
                ->after('max_categorias');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->dropColumn([
                'max_productos',
                'max_redes_sociales',
                'tipo_estadisticas',
                'max_imagenes_producto',
                'max_categorias',
                'seo_level',
            ]);
        });
    }
};
