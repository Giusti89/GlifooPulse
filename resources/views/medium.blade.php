<x-layouts.spotbasic titulo="{{ $titulo }}" icono="{{ '/storage/' . $contenido->logo_url }}"
    backgroud="{{ $contenido->background }}">
    <link rel="stylesheet" href="{{ asset('./estilo/medium.css') }}">
    <div class="basicoprincipal">
        <div class="sideA">
            <div class="banner">
                <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="Banner de portada" class="img-banner">
                <div class="logo-perfil">
                    <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
                </div>
            </div>

            <div class="titulo">
                <h1> <b>{{ $titulo }}</b> </h1>
            </div>

            <div class="descrip">
                <p>
                    {{ $contenido->texto }}
                </p>
            </div>

            <div class="map">
                <div class="titulo">
                    <h4> <b>Dirección</b> </h4>
                </div>
                <div class="direc">
                    {{ $contenido->pie }}
                </div>

                <div class="mapframe">
                    <iframe
                        src="https://www.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&hl=es&z=16&output=embed"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <div class="sideC">
            <h4> <b>Enlaces</b> </h4>
            <div class="red">
                @foreach ($redes as $item)
                    @php
                        $encryptedId = Crypt::encrypt($item->id);
                    @endphp
                    <div class="redes">
                        <a href="{{ route('redireccion', $encryptedId) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('/storage/' . $item->image_url) }}" alt="{{ $item->nombre }}">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</x-layouts.spotbasic>
