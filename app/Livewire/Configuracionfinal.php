<?php

namespace App\Livewire;

use App\Models\Spot;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;


class Configuracionfinal extends Component
{
    // Cambiamos a propiedad pública normal para evitar bloqueos de hidratación antiguos
    public $spotId;

    public function mount()
    {
        $this->cargarDatosUsuario();
    }

    /**
     * Forzamos la carga de datos frescos en cada renderizado para evitar 
     * fugas de información entre sesiones o cambios de usuario.
     */
    public function cargarDatosUsuario()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return abort(403);
        }

        $spot = Spot::with('suscripcion', 'socials')
            ->whereHas('suscripcion', function ($q) use ($usuario) {
                $q->where('user_id', $usuario->id);
            })
            ->firstOrFail();

        $this->spotId = $spot->id;
    }

    /**
     * Propiedad computada dinámica. Garantiza que si cambias de usuario 
     * o refrescas, la consulta se ejecute nuevamente con el ID correcto.
     */
    public function getSpotProperty()
    {
        return Spot::with('socials')->find($this->spotId) ?? $this->cargarDatosUsuario();
    }

    public function toggleEstado()
    {
        $spot = $this->spot;
        $spot->estado = !$spot->estado;
        $spot->save();

        Notification::make()
            ->title($spot->estado ? "¡Su web esta activa!" : "¡Su web esta inactiva!")
            ->icon('heroicon-o-user')
            ->iconColor($spot->estado ? 'success' : 'danger')
            ->send();
    }

    public function render()
    {
        // Forzar recarga si cambia el usuario autenticado en caliente
        $this->cargarDatosUsuario();

        return view('livewire.configuracionfinal', [
            'spot' => $this->spot
        ]);
    }
}
