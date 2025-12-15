<x-layouts.principal titulo="Nuestros Socios">
    <link rel="stylesheet" href="{{ asset('estilo/socios.css') }}">

    <div class="socios-container">
        <div class="socios-header">
            <h1 class="socios-titulo">Nuestros Socios Pulse</h1>
            <p class="socios-subtitulo">Descubre las empresas innovadoras que forman parte de nuestra comunidad</p>
        </div>

        <div class="socios-grid">
            @foreach ($results as $item)
                @if ($item->estado == true)
                    <a href="{{ route('publicidad', $item->spot_slug) }}" class="socios-card">
                        <div class="card-bg" style="background-image: url('{{ Storage::url($item->logo_url) }}')">
                            <div class="card-overlay"></div>
                            <div class="card-content">
                                <h3>{{ $item->titulo }}</h3>
                                <span class="card-badge">Socio Pulse</span>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
        <!-- Contador de socios -->
    </div>
</x-layouts.principal>
