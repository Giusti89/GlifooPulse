<?php

use App\Models\Spot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suport_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spot_id')
                ->constrained('spots')
                ->cascadeOnDelete()
                ->unique(); // 🔑 Un solo registro por spot

            // Colores principales (con valores por defecto)
            $table->string('fondocolor', 20)->default('#ffffff');
            $table->string('secondary', 20)->default('#333333');
            $table->string('text', 20)->default('#333333');

            // Colores adicionales (nullables)
            $table->string('primary_button', 20)->nullable();
            $table->string('button_text', 20)->nullable();
            $table->string('header', 20)->nullable();
            $table->string('footer', 20)->nullable();

            $table->timestamps();
        });
        $this->initializeColorsForExistingSpots();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suport_colors');
    }
    
    private function initializeColorsForExistingSpots(): void
    {
        $spots = Spot::all();

        if ($spots->isEmpty()) {
            return;
        }

        $now = now();
        $colorsData = [];

        foreach ($spots as $spot) {
            $colorsData[] = [
                'spot_id' => $spot->id,
                'fondocolor' => '#ffffff',
                'secondary' => '#333333',
                'text' => '#333333',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Inserción masiva (más eficiente)
        DB::table('suport_colors')->insert($colorsData);
    }
};
