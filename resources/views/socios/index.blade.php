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
                        <x-layouts.btnenviodat rutaEnvio="publicidad" dato="{{ $item->spot_slug }}"
                            nombre="{{ $item->user_name }}">
                        </x-layouts.btnenviodat>
                    </div>
                @endif
            @endforeach
        </div>

    </div>




</x-layouts.principal>
