<x-layouts.spotbasic titulo="{{ $titulo }}" icono="{{ '/storage/' . $contenido->logo_url }}"
    backgroud="{{ $contenido->background }}">
    <link rel="stylesheet" href="{{ asset('./estilo/basico.css') }}">
    <div class="basicoprincipal text-white"> {{-- APLICADO TEXTO BLANCO A TODO --}}
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
                <div class="texto text-gray-200"> {{-- Más suave, para descripción --}}
                    <p>{{ $contenido->texto }}</p>
                </div>
                <div class="redes">
                    
                    <div class="cajabot">
                        @foreach ($redes as $item)
                            @php
                                $encryptedId = Crypt::encrypt($item->id);
                            @endphp
                            <div class="boton">
                                <a href="{{ route('redireccion', $encryptedId) }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('/storage/' . $item->image_url) }}" alt="{{ $item->nombre }}">
                                </a>
                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="direccion text-gray-300"> {{-- Pie de página --}}
                <p>{!! $contenido->pie !!}</p>
            </div>
        </div>
    </div>
</x-layouts.spotbasic>
