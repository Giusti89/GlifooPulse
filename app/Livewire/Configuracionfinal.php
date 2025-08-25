<?php

namespace App\Livewire;

use App\Models\Spot;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;


class Configuracionfinal extends Component
{
    public Spot $spot;
    public $usuario;

    public function mount()
    {
        // 1. Guarda el usuario autenticado si lo necesitas en la vista
        $this->usuario = Auth::user();

        // 2. Obtén el spot que pertenezca a la suscripción de este usuario
        $this->spot = Spot::with('suscripcion')
            ->whereHas('suscripcion', function ($q) {
                $q->where('user_id', $this->usuario->id);
            })
            ->firstOrFail(); // 404 si no existe o no pertenece

        // A partir de aquí $this->spot está garantizado para este usuario
    }
    public function toggleEstado()
    {
        $this->spot->estado = ! $this->spot->estado;
        $this->spot->save();

        if ( $this->spot->estado) {
            Notification::make()
            ->title("¡Su web esta activa!")
            ->icon('heroicon-o-user')
            ->iconColor('success')
            ->send();
        }else{
             Notification::make()
            ->title("¡Su web esta inactiva!")
            ->icon('heroicon-o-user')
            ->iconColor('danger')
            ->send();
        }
    }
    public function copiarEnlace()
    {
        $this->dispatch('copiar-enlace', enlace: url($this->spot->slug));

        Notification::make()

            ->title("¡Su enlace fue copiado!")
            ->icon('heroicon-o-user')
            ->iconColor('success')
            ->send();
    }
    public function render()
    {
        return view('livewire.configuracionfinal');
    }
}
