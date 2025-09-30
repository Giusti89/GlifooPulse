<x-layouts.plantilla :titulo="$titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg"
    :locale="$locale" :backgroud="$contenido->background" :icono="'/storage/' . $contenido->logo_url">
    <link rel="stylesheet" href="{{ asset('estilo/medium.css') }}">

    <div class="basicoprincipal">
        <div class="sideA">
            <div class="banner">
                <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="Banner de portada" class="img-banner">
                <div class="logo-perfil">
                    <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
                </div>
            </div>

            <div class="titulo" style="color:{{ $contenido->ctexto }}">
                <h1><b>{{ $titulo }}</b></h1>
            </div>

            <div class="descrip">
                <p style="color:{{ $contenido->ctexto }}">
                    {{ $contenido->texto }}
                </p>
            </div>

            <div class="map">
                <div class="titulo" style="color:{{ $contenido->ctexto }}">
                    <h4><b>Dirección</b></h4>
                </div>

                <div class="mapframe">
                    <iframe
                        src="https://www.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&hl=es&z=16&output=embed"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" title="Mapa de ubicación">
                    </iframe>
                    <div class="direc" style="color:{{ $contenido->ctexto }}">
                        {{ $contenido->pie }}
                    </div>
                </div>
            </div>
        </div>

        <div class="sideC">
            <div class="red">
                {{-- Redes Sociales --}}
                <div class="redS">
                    @php
                        $otrasRedes = $redes->where('tipoRed.nombre', 'Red Social');
                    @endphp
                    @if ($otrasRedes->isNotEmpty())
                        <div class="subtitulo">
                            <h4 class="redesT" style="color:{{ $contenido->ctexto }}"><b>Redes Sociales</b></h4>
                        </div>
                        <div class="sociales">
                            @foreach ($redes->where(fn($red) => optional($red->tipoRed)->nombre === 'Red Social' || $red->tipoRed === null) as $item)
                                @php
                                    $encryptedId = Crypt::encrypt($item->id);
                                @endphp
                                <div class="redes">
                                    <a href="{{ route('redireccion', $encryptedId) }}" target="_blank"
                                        rel="noopener noreferrer">
                                        <img src="{{ asset('/storage/' . $item->image_url) }}"
                                            alt="{{ $item->nombre }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                {{-- Otras Redes --}}
                <div class="ored">
                    @php
                        $otrasRedes = $redes->where('tipoRed.nombre', 'Otra Red');
                    @endphp
                    @if ($otrasRedes->isNotEmpty())
                        <div class="subtitulo">
                            <h4 class="redesT" style="color:{{ $contenido->ctexto }}"><b>Otros Enlaces</b></h4>
                        </div>
                    @endif
                    <div class="otro">
                        @if ($otrasRedes->isNotEmpty())
                            @foreach ($otrasRedes as $item)
                                @php
                                    $encryptedId = Crypt::encrypt($item->id);
                                    $colorTexto = $contenido->ctexto ?? '#000000';
                                @endphp
                                <div class="redes">
                                    <a href="{{ route('redireccion', $encryptedId) }}" target="_blank"
                                        rel="noopener noreferrer"
                                        style="text-decoration: none; color: {{ $colorTexto }}">
                                        <div class="otrared"
                                            style="background-image: url(/storage/{{ $item->image_url }}); color: {{ $colorTexto }}">
                                            <p style="color: {{ $colorTexto }}">
                                                <b>{{ $item->nombre }}</b>
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.plantilla>
