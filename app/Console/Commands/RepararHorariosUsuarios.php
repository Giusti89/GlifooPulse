<?php

namespace App\Console\Commands;

use App\Models\Landing;
use App\Models\Spot;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RepararHorariosUsuarios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reparar-horarios-usuarios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando la migración y reparación de horarios para usuarios antiguos...');

        // 1. Traemos todos los spots con sus relaciones necesarias para verificar el tipo
        $spots = Spot::all();
        $spotsReparados = 0;

        foreach ($spots as $spot) {
            // 2. Buscamos la landing/plantilla vinculada a este spot
            $landing = Landing::find($spot->tipolanding);

            // 3. Evaluamos si el grupo es de tipo 'catalogo'
            $esCatalogo = $landing && Str::slug($landing->grupo) === 'catalogo';

            if ($esCatalogo) {
                // 4. Verificamos si este catálogo ya cuenta con filas en la tabla de horarios
                if ($spot->horarios()->count() === 0) {

                    $this->line("🔧 Generando horarios para el Spot ID: {$spot->id} (Link: {$spot->slug})");

                    // 5. Insertamos los 7 días fijos de lunes a domingo
                    foreach (range(1, 7) as $diaNumero) {
                        $spot->horarios()->create([
                            'dia' => $diaNumero,
                            'apertura' => '08:00:00',
                            'cierre' => '18:00:00',
                            'esta_cerrado' => false,
                        ]);
                    }

                    $spotsReparados++;
                }
            }
        }

        $this->info("✅ ¡Proceso terminado con éxito! Se crearon los horarios de la semana para {$spotsReparados} catálogos antiguos.");

        return Command::SUCCESS;
    }
}
