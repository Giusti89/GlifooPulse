<div>
    <link rel="stylesheet" href="{{ asset('./estilo/renovacion.css') }}">
    <div class="form-container">
        <h1 class="form-title">Glifoo Pulse</h1>
        <h2 class="form-subtitle">Renovación de suscripción</h2>

        <form wire:submit.prevent="renovar">
            <!-- Meses de renovación -->
            <div class="form-group">
                <label>Costo mensual</label>
                <input type="text" value="{{ number_format($sus->paquete->precio, 2) }} Bs." readonly style="color: black">

                <label>Meses de renovación</label>
                <select wire:model="meses" @disabled($isSubmitting)>
                    <option value="1">1 mes - {{ number_format($sus->paquete->precio * 1, 2) }} Bs.</option>
                    <option value="3">3 meses - {{ number_format($sus->paquete->precio * 3, 2) }} Bs.</option>
                    <option value="6">6 meses - {{ number_format($sus->paquete->precio * 6, 2) }} Bs.</option>
                </select>
            </div>

            <!-- Botón con estados -->
            <button type="submit" wire:loading.attr="disabled" class="relative">
                <span wire:loading.remove>Renovar</span>
                <span wire:loading>
                    <svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                alert(event.message); // Puedes reemplazar con SweetAlert o similar
            });
        });
    </script>
@endpush
