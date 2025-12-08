<x-layouts.renovacion titulo="Renovacion de servicio" url="{{ asset('./estilo/renovacion.css') }}">

    <div class="form-container">
        <h1 class="form-title">Glifoo Pulse</h1>
        <h2 class="form-subtitle">Renovación de suscripción</h2>

        <form method="POST" action="{{ route('resuscripcion.store', ['renovacion' => $encryptedId]) }}">
            @csrf <!-- Token de seguridad obligatorio -->

            <div class="form-group">
                <label>Meses de renovación</label>
                <select name="meses" id="meses" @if ($isSubmitting) disabled @endif>
                    <option value="1">1 mes - {{ number_format($user->suscripcion->paquete->precio * 1, 2) }} Bs.
                    </option>
                    <option value="3">3 meses - {{ number_format($user->suscripcion->paquete->precio * 3, 2) }} Bs.
                    </option>
                    <option value="6">6 meses - {{ number_format($user->suscripcion->paquete->precio * 6, 2) }} Bs.
                    </option>
                     <option value="12">12 meses - {{ number_format($user->suscripcion->paquete->precio * 12, 2) }} Bs.
                    </option>
                </select>
            </div>

            <button type="submit" @if ($isSubmitting) disabled @endif>
                @if ($isSubmitting)
                    <span class="spinner"></span> Procesando...
                @else
                    Renovar
                @endif
            </button>
        </form>

        <a style="text-decoration: none;color:white" href="{{ route('inicio') }}">
            <button type="cancel" style="background:red">volver</button>
        </a>

    </div>

</x-layouts.renovacion>

<script>
    document.querySelector('form').addEventListener('submit', function() {
        this.querySelector('button[type="submit"]').disabled = true;
        this.querySelector('button[type="submit"]').innerHTML = `
        <span class="spinner"></span> Procesando...
    `;
    });
</script>
