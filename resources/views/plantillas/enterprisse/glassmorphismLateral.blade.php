<x-layouts.plantilla :titulo="$titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg"
    :locale="$locale" :backgroud="$contenido->background" :icono="'/storage/' . $contenido->logo_url">

    <link rel="stylesheet" href="{{ asset('./estilo/lateral.css') }}">

    <div class="catalogo-contenedor">

        <!-- Banner tipo cabecera -->
        <div class="banner-fondo">
            <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="Banner de portada">
            <div class="banner-overlay"></div>
        </div>

        <!-- Contenido central con glass -->
        <div class="glass-card central">
            <div class="logo-perfil">
                <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
            </div>
            <h1 class="titulo" style="color:{{ $contenido->ctexto }}">{{ $titulo}}</h1>
            <p class="descrip" style="color:{{ $contenido->ctexto }}">{{ $contenido->texto }}</p>
        </div>

        <!-- Redes sociales -->
        @php
            $redesSociales = $redes->where('tipoRed.nombre', 'Red Social');
            $otrasRedes = $redes->where('tipoRed.nombre', 'Otra Red');
        @endphp

        @if ($redesSociales->isNotEmpty())
            <div class="glass-card redes">
                <h3 style="color:{{ $contenido->ctexto }}">Redes Sociales</h3>
                <div class="social-icons">
                    @foreach ($redesSociales as $item)
                        @php $encryptedId = Crypt::encrypt($item->id); @endphp
                        <a href="{{ route('redireccion', $encryptedId) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('/storage/' . $item->image_url) }}" alt="{{ $item->nombre }}">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Otros Enlaces -->
        @if ($otrasRedes->isNotEmpty())
            <div class="glass-card otros-enlaces">
                <h3 style="color:{{ $contenido->ctexto }}">Otros Enlaces</h3>
                <div class="otro">
                    @foreach ($otrasRedes as $item)
                        @php
                            $encryptedId = Crypt::encrypt($item->id);
                            $colorTexto = $contenido->ctexto ?? '#000';
                        @endphp
                        <a href="{{ route('redireccion', $encryptedId) }}" target="_blank" rel="noopener"
                            class="otrared"
                            style="background-image: url('/storage/{{ $item->image_url }}'); color: {{ $colorTexto }}">
                            <p><b>{{ $item->nombre }}</b></p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Mapa -->
        <div class="glass-card mapa">
            <h4>Dirección</h4>
            <iframe
                src="https://www.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&hl=es&z=16&output=embed"
                width="100%" height="250" style="border:0;" allowfullscreen loading="lazy">
            </iframe>
            <p>{{ $contenido->pie }}</p>
        </div>
    </div>
</x-layouts.plantilla>