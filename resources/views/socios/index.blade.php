<x-layouts.principal titulo="Nuestros-Socios">
    <link rel="stylesheet" href="{{ asset('./estilo/socios.css') }}">

    <div class="cuerpo">
        <div class="titulo">
            <h1>Nuestros Socios Pulse</h1>
        </div>
        <div class="botones">
            @foreach ($results as $item)
                @if ($item->estado == true)
                    <div class="boton">
                        <a href="{{ route('publicidad', $item->spot_slug) }}">
                            <div class="tarjeta" style="background-image: url(/storage/{{ $item->logo_url }})">
                            </div>
                            <div class="subtitulo">
                                {{ $item->titulo }}
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>

    </div>




</x-layouts.principal>
