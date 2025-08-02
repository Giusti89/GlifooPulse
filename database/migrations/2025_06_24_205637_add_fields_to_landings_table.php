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
        Schema::table('landings', function (Blueprint $table) {

            $table->string('preview_url')->nullable()->after('descripcion');
            $table->boolean('pago')->default(false)->after('preview_url');
            $table->decimal('precio', 10, 2)->nullable()->after('pago');
            $table->foreignId('paquete_id')->nullable()->constrained('paquetes')->onDelete('set null')->after('precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landings', function (Blueprint $table) {
            $table->dropForeign(['paquete_id']);
            $table->dropColumn(['preview_url', 'pago', 'precio', 'paquete_id']);
        });
    }
};
