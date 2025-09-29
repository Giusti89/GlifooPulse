<x-layouts.plantilla 
    :titulo="$titulo" 
    :descripcion="$contenido->descripcion_seo ?? null" 
    :keywords="$contenido->keywords_seo ?? null" 
    :imagenOg="'/storage/' . $contenido->banner_url" 
    :backgroud="$contenido->background" 
    :icono="'/storage/' . $contenido->logo_url">

    <link rel="stylesheet" href="{{ asset('./estilo/ventana.css') }}">

    <div class="catalogo-contenedor">

        <!-- Banner de fondo -->
        <div class="banner-fondo">
            <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="Banner de portada">
        </div>

        <!-- Contenido central con glass -->
        <div class="glass-card central">
            <div class="logo-perfil">
                <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
            </div>
            <h1 class="titulo">{{ $titulo }}</h1>
            <p class="descrip">{{ $contenido->texto }}</p>
        </div>

        <!-- Redes sociales -->
        @if($redes->isNotEmpty())
            <div class="glass-card redes">
                <h3>Conecta con nosotros</h3>
                <div class="social-icons">
                    @foreach ($redes as $item)
                        @php $encryptedId = Crypt::encrypt($item->id); @endphp
                        <a href="{{ route('redireccion', $encryptedId) }}" target="_blank">
                            <img src="{{ asset('/storage/' . $item->image_url) }}" alt="{{ $item->nombre }}">
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
                width="100%" 
                height="250" 
                style="border:0;" 
                allowfullscreen 
                loading="lazy">
            </iframe>
            <p>{{ $contenido->pie }}</p>
        </div>
    </div>
</x-layouts.plantilla>