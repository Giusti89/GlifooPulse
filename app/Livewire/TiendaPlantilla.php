<?php

namespace App\Livewire;

use App\Mail\Plantilla;
use App\Models\Landing;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class TiendaPlantilla extends Component
{
    use WithPagination;
    public $usuario;
    public $pendientes = [];

    public function mount()
    {
        $user = Auth::user();

        if ($user && $user->suscripcion) {
            $this->pendientes = Sell::query()
                ->where('suscripcion_id', $user->suscripcion->id)
                ->where('concepto', 'plantilla')
                ->where('pago', 0)
                ->pluck('landing_id')
                ->toArray();
        }
    }
    public function comprar($landingId)
    {
        $usuario = Auth::user();
        $landing = Landing::findOrFail($landingId);


        if ($usuario->landingsCompradas->contains($landing->id)) {
            $this->dispatchBrowserEvent('notification', [
                'type' => 'error',
                'message' => 'Ya compraste esta plantilla.'
            ]);
            return;
        }

        // Crear venta pendiente
        Sell::create([

            'concepto' => 'plantilla',
            'total' => $landing->precio,
            'pago' => 0,
            'estadov_id' => 1,
            'fecha' => now(),
            'suscripcion_id' => $usuario->suscripcion->id,
            'landing_id'     => $landing->id,
        ]);
        $this->pendientes[] = $landingId;


        $adminEmails = User::where('rol_id', 1)->pluck('email')->toArray();
        
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new Plantilla($usuario, $landing));
        }

        Notification::make()

            ->title("¡Solicitud recibida! Te avisaremos cuando confirmes el pago!")
            ->icon('heroicon-o-user')
            ->iconColor('success')
            ->send();
    }
    public function render()
    {
        $usuario = Auth::user();

        $landingsPago = $usuario->suscripcion->paquete->landings()
            ->where('pago', true)
            ->get();

        return view('livewire.tienda-plantilla', compact('landingsPago'));
    }
}
