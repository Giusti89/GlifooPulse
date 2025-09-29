<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <link rel="stylesheet" href="{{ asset('./estilo/viplantilla.css') }}">

    @php
        $landing = $getState();
    @endphp

    @if ($landing && is_array($landing))
        <div class="landing-preview-container">
            <img src="{{ $landing['preview_url'] }}" alt="Vista previa" class="landing-preview-image">

            <div class="landing-preview-grid">
                <div class="landing-preview-card">
                    <p class="landing-preview-label">Nombre:</p>
                    <p>{{ $landing['nombre'] }}</p>
                </div>

                <div class="landing-preview-card">
                    <p class="landing-preview-label">Descripción:</p>
                    <p>{{ $landing['descripcion'] }}</p>
                </div>

                <div class="landing-preview-card">
                    <p class="landing-preview-label">Tipo:</p>
                    <p class="landing-preview-premium">{{ $landing['pago'] ? 'Premium' : 'Gratuita' }}</p>
                </div>

                @if ($landing['pago'])
                    <div class="landing-preview-card landing-preview-fullwidth">
                        <p class="landing-preview-label">Precio:</p>
                        <p>${{ number_format($landing['precio'], 2) }}</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="landing-preview-placeholder">
            Selecciona una plantilla para ver la vista previa
        </div>
    @endif
</x-dynamic-component>
