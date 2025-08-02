<x-layouts.plantilla :titulo="$titulo" :descripcion="$contenido->descripcion_seo ?? null" :keywords="$contenido->keywords_seo ?? null" :imagenOg="'/storage/' . $contenido->banner_url" :backgroud="$contenido->background" :
    icono="{{ '/storage/' . $contenido->logo_url }}">
    <link rel="stylesheet" href="{{ asset('./estilo/basico.css') }}">
    <div class="basicoprincipal" style="color:{{ $contenido->ctexto }}"> {{-- APLICADO TEXTO BLANCO A TODO --}}
        <div class="banner">
            <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="Banner de portada" class="img-banner">
            <div class="logo-perfil">
                <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
            </div>
        </div>
        <div class="titulo">
            <h1><b>{{ $titulo }}</b></h1>
        </div>
        <div class="cuerpo">
            <div class="seccionuno">
                <div class="text" style="color:{{ $contenido->ctexto }}"> 
                    <p>{{ $contenido->texto }}</p>
                </div>
            </div>
            <div class="redes">
                <div class="contenedor">
                    @php
                        $otrasRedes = $redes->where('tipoRed.nombre', 'Red Social');
                    @endphp

                    @if ($otrasRedes->isNotEmpty())
                        <div class="subtitulo">
                            <h3 style="color:{{ $contenido->ctexto }}"> <b>Redes Sociales</b> </h3>
                        </div>
                        <div class="sociales">
                            @foreach ($redes->where(fn($red) => optional($red->tipoRed)->nombre === 'Red Social' || $red->tipoRed === null) as $item)
                                @php
                                    $encryptedId = Crypt::encrypt($item->id);
                                @endphp
                                <div class="redes">
                                    <a href="{{ route('redireccion', $encryptedId) }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('/storage/' . $item->image_url) }}"
                                            alt="{{ $item->nombre }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>


                <div class="ored">
                    <div class="contenedor">
                        @php
                            $otrasRedes = $redes->where('tipoRed.nombre', 'Otra Red');
                        @endphp
                        @if ($otrasRedes->isNotEmpty())
                            <div class="subtitulo">
                                <h3 style="color:{{ $contenido->ctexto }}"><b>Otros Enlaces</b></h3>
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
                                            rel="noopener" style="text-decoration: none; color: {{ $colorTexto }}">
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
        <div class="direccion" style="color:{{ $contenido->ctexto }}"> {{-- Pie de página --}}
            <p>{!! $contenido->pie !!}</p>
        </div>
    </div>
</x-layouts.plantilla>
