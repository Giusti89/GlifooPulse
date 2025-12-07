@php

    $bgColor = $contenido->background ?? '#ffffff';
    $textColor = $contenido->ctexto ?? '#333333';
    $colsec = $contenido->colsecond ?? '#333333';
    $whatsNumber = Str::of($contenido->phone ?? '')
        ->replaceMatches('/\D+/', '')
        ->__toString();
@endphp

<x-layouts.plantillacatalogo :titulo="$titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg"
    :locale="$locale" :backgroud="$contenido->background" :icono="'/storage/' . $contenido->logo_url">
    <style>
        :root {
            --brand-background: {{ $bgColor }};
            --brand-text: {{ $textColor }};
            --brand-secondary: {{ $colsec }};
        }
    </style>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="{{ asset('estilo/tooplate-stellaris-style.css') }}?v={{ filemtime(public_path('estilo/tooplate-stellaris-style.css')) }}">

    <div class="catalogo-content">
        <canvas id="starfield"></canvas>
        @if ($redes->count() > 0)
            <div class="redes-sociales-sticky">
                <div class="redes-sociales-container">
                    @foreach ($redes as $red)
                        @php
                            $encryptedId = Crypt::encrypt($red->id);
                        @endphp

                        <a href="{{ route('redireccion', $encryptedId) }}" class="red-social-link" target="_blank">
                            @if ($red->image_url)
                                <img src="{{ asset('/storage/' . $red->image_url) }}" alt="{{ $red->nombre }}"
                                    class="red-social-icon">
                            @else
                                <span class="red-social-text">{{ substr($red->nombre, 0, 2) }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <nav id="navbar">
            <div class="nav-container">
                <a href="#home" class="logo-container">
                    <span class="logo-text">{{ $titulo }}</span>
                </a>

                <div class="mobile-menu-toggle" id="mobile-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="nav-menu" id="nav-menu">
                    <ul>
                        <li><a href="#home">HOME</a></li>
                        <li><a href="#nosotros">NOSOTROS</a></li>
                        <li><a href="#catalogo">CATALOGO</a></li>
                        <li><a href="#mapa">MAPA</a></li>

                    </ul>
                </div>
            </div>
        </nav>

        <section id="home" class="hero" style="background-image: url('/storage/{{ $contenido->banner_url }}')">
            <div class="hero-content">
                <h1 class="cosmic-title">{{ $titulo }}</h1>
            </div>
            <div class="logo-perfil">
                <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
            </div>
        </section>

        <!-- Modal  -->
        <div id="modalImagen" class="modal">
            <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
            <img class="modal-contenido" id="imagenModal">
            <div id="tituloModal" class="modal-titulo"></div>
        </div>
        <div id="consultaModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <button class="modal-close" onclick="cerrarConsulta()">&times;</button>

                <h2 id="modalProductoNombre">Consultar producto</h2>
                <img id="modalProductoImagen" src="" alt="" class="modal-imagen">

                <form id="consultaForm" method="POST" target="_blank" action="">
                    @csrf
                    <input type="hidden" id="productoId" name="producto_id">

                    <div class="form-group">
                        <label for="nombre">Nombre (opcional)</label>
                        <input type="text" name="nombre" id="nombre" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono (opcional)</label>
                        <input type="text" name="telefono" id="telefono" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea name="mensaje" id="mensaje" class="form-control" required placeholder="Hola, me interesa el producto..."></textarea>
                    </div>

                    <button type="submit" class="btn-enviar">Enviar por WhatsApp</button>
                </form>
            </div>
        </div>
        <!-- Modal  -->
        <section id="nosotros" class="missions-section fade-in">
            <div class="nosotros-container">
                <h2 class="section-title">NOSOTROS</h2>
                <p>
                    {{ $contenido->texto }}
                </p>
            </div>
        </section>

        <section id="catalogo" class="missions-section fade-in">
            <div class="missions-container">
                <h2 class="section-title">CATALOGO DE PRODUCTOS</h2>

                <div class="mission-tabs-scroll-container">
                    <div class="sticky-tabs-container">
                        <div class="mission-tabs-scroll-container">
                            <div class="scroll-indicator left" id="scrollLeft">‹</div>
                            <div class="mission-tabs-scroll" id="stickyTabs">
                                @foreach ($categoriapro as $categoria)
                                    <button class="mission-tab {{ $loop->first ? 'active' : '' }}"
                                        data-tab="categoria-{{ $categoria->id }}">
                                        {{ $categoria->nombre }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="scroll-indicator right" id="scrollRight">›</div>
                        </div>
                    </div>

                    @foreach ($categoriapro as $categoria)
                        <div class="mission-content {{ $loop->first ? 'active' : '' }}"
                            id="categoria-{{ $categoria->id }}">
                            <h2 class="categoria-titulo">{{ strtoupper($categoria->nombre) }}</h2>
                            <div class="mission-grid">
                                @foreach ($categoria->productos as $producto)
                                    @php
                                        $imagen = $producto->imagenes->first();
                                        $src = $imagen
                                            ? Storage::url($imagen->url)
                                            : asset('img/placeholder-producto.jpg');
                                    @endphp

                                    <div class="mission-card product-card">
                                        <div class="producto-imagen"
                                            onclick="abrirModal('{{ $src }}', '{{ $producto->nombre }}')"
                                            role="button" tabindex="0"
                                            aria-label="Ver imagen completa de {{ $producto->nombre }}">

                                            <img src="{{ $src }}" data-src="{{ $src }}"
                                                alt="{{ $producto->nombre }}" class="img-producto" loading="lazy"
                                                width="400" height="400"
                                                onerror="this.src='/images/placeholder.jpg'; this.alt='Imagen no disponible'">
                                        </div>
                                        <h4>{{ $producto->nombre }}</h4>
                                        <p>{{ Str::limit($producto->descripcion, 250) }}</p>

                                        @if ($producto->precio)
                                            <div class="product-price">${{ number_format($producto->precio, 2) }}
                                            </div>
                                        @endif

                                        <button type="button" class="producto-whatsapp"
                                            onclick="abrirConsulta('{{ $src }}', '{{ $producto->nombre }}', '{{ $producto->id }}')">
                                            Contactar por WhatsApp
                                        </button>

                                        <span class="mission-status estado-{{ Str::slug($producto->estado) }}">
                                            {{ $producto->estado }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
        </section>

        <section id="mapa" class="mapa-avanzado">
            <div class="contenedor-principal">
                <h2 class="titulo-destacado">ENCUÉNTRANOS</h2>
                <div class="tarjeta-mapa">
                    <div class="mapa-3d">
                        <iframe
                            src="https://www.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&hl=es&z=16&output=embed"
                            width="100%" height="450" style="border: 0; border-radius: 8px;" allowfullscreen
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Mapa de ubicación">
                        </iframe>
                    </div>
                    <div class="info-overlay" style="color: {{ $contenido->background }};">
                        <div class="icono-mapa">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                        </div>
                        <p class="direccion-texto">{{ $contenido->pie }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script
        src="{{ asset('./dinamico/tooplate-stellaris-script.js') }}?v={{ filemtime(public_path('./dinamico/tooplate-stellaris-script.js')) }}">
    </script>
</x-layouts.plantillacatalogo>
