<x-layouts.spotbasic titulo="{{ $titulo }}" icono="{{ '/storage/' . $contenido->logo_url }}">
    <link rel="stylesheet" href="{{ asset('./estilo/basico.css') }}">
    <div class="basicoprincipal">
        <div class="banner">
            <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="Banner de portada" class="img-banner">
            <div class="logo-perfil">
                <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
            </div>
        </div>
        <div class="titulo">
            <h1> <b>{{ $titulo }}</b> </h1>
        </div>
        <div class="cuerpo">
            <div class="seccionuno">
                <div class="texto">
                    <p>{{ $contenido->texto }}
                    </p>
                </div>
                <div class="redes">
                    @foreach ($redes as $item)
                        @php
                            $encryptedId = Crypt::encrypt($item->id);
                        @endphp
                        <a href="{{ route('redireccion', $encryptedId) }}" target="_blank" rel="noopener">
                            <img src="{{ asset('/storage/' . $item->image_url) }}" alt="">
                        </a>
                    @endforeach


                </div>
            </div>
            <div class="direccion">
                <p>
                    {!! $contenido->pie !!} <br>
                </p>

            </div>

        </div>
    </div>
</x-layouts.spotbasic>
