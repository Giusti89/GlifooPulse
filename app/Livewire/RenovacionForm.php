<?php

namespace App\Livewire;

use App\Mail\Renovacion;
use App\Models\Renewal;
use App\Models\Sell;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;


#[Layout('components.layouts.renovacion', ['titulo' => 'Renovación'])]
class RenovacionForm extends Component
{

    public $meses = 1;
    #[Locked]
    public $suscripcion;
    public $isSubmitting = false;

    public function mount()
    {
        $this->suscripcion = auth()->user()->suscripcion;
    }

    public function renovar()
    {

        $this->isSubmitting = true;

        try {
            $this->validate([
                'meses' => 'required|integer|min:1',
            ]);

            Renewal::create([
                'suscripcion_id' => $this->suscripcion->id,
                'fecha' => now(),
                'meses' => $this->meses,
                'estado' => 'pendiente', // Estado inicial
            ]);

            $cuenta = Sell::create([
                'suscripcion_id' => $this->suscripcion->id,
                'total' => number_format($this->suscripcion->paquete->precio * $this->meses, 2),
                'fecha' => now(),
            ]);

            $adminEmails = User::where('rol_id', 1)->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new Renovacion(
                    auth()->user(),
                    $this->suscripcion->paquete,
                    $this->meses
                ));
            }

            Notification::make()
                ->title('Su solicitud a sido enviada')
                ->success()
                ->send();

            return redirect()->route('filament.usuario.resources.spots.index');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }


        // Crear solicitud de renovación (NO actualizar fecha_fin aún)

    }

    public function render()
    {
        $sus = auth()->user()->suscripcion;
        return view('livewire.renovacion-form', compact('sus'));
    }
}
