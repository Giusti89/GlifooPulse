@php

    $bgColor = $contenido->background ?? '#ffffff';
    $textColor = $contenido->ctexto ?? '#333333';
    $colsec = $contenido->colsecond ?? '#333333';
    $whatsNumber = Str::of($contenido->phone ?? '')
        ->replaceMatches('/\D+/', '')
        ->__toString();
@endphp

<x-layouts.plantillacatalogo :titulo="$tituloSEO ?? $titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg"
    :locale="$locale" :backgroud="$contenido->background" :icono="'/storage/' . $contenido->logo_url" :ogUrl="$ogUrl" :ogType="$ogType" :contenido="$contenido"
    :categoriapro="$categoriapro">
    <style>
        :root {
            --brand-background: {{ $bgColor }};
            --brand-text: {{ $textColor }};
            --brand-secondary: {{ $colsec }};
        }
    </style>
    <link rel="stylesheet"
        href="{{ asset('estilo/catalogob.css') }}?v={{ filemtime(public_path('estilo/catalogob.css')) }}">

    <div class="catalogo-content">
        <!-- Barra de redes sociales sticky -->
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

        <div class="banner">
            <img src="{{ asset('/storage/' . $contenido->banner_url) }}" alt="Banner de portada" class="img-banner">
            <div class="logo-perfil">
                <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="Logo de perfil">
            </div>

        </div>
        <h1 class="catalogo-titulo">{{ $titulo ?? 'Mi Catálogo' }}</h1>
        <h2 class="catalogo-subtitulo">{{ $contenido->subtitulo_hero ?? '' }}</h2>


        <section id="nosotros" class="missions-section fade-in">
            <div class="nosotros-container">
                <h2 class="categoria-titulo">NOSOTROS</h2>
                <p>
                    {{ $contenido->texto }}
                </p>
            </div>
        </section>

        <section id="video" class="missions-section fade-in">
            @if (isset($videos) && $videos->count() > 0)
                <h2 class="categoria-titulo">VIDEO PROMOCIONAL</h2>
                <div class="video-container">
                    <div class="hero-visual">
                        @include('partials.reproductor-videos', ['videos' => $videos])
                    </div>
                </div>
            @endif
        </section>

        @if ($categoriapro->count() > 0)
            <!-- Navegación por categorías -->
            <h2 class="categoria-titulo">PRODUCTOS</h2>
            <div class="categorias-navegacion">
                @foreach ($categoriapro as $categoria)
                    <a href="{{ url()->current() }}#{{ $categoria->slug }}" class="categoria-link">
                        {{ $categoria->nombre }}
                    </a>
                @endforeach
            </div>

            <!-- Lista de categorías con productos -->
            @foreach ($categoriapro as $categoria)
                <section id="{{ $categoria->slug }}" class="categoria-productos">
                    <h3 class="categoria-titulo">{{ strtoupper($categoria->nombre) }}</h3>

                    @if ($categoria->productos->isNotEmpty())
                        <div class="productos-grid">
                            @foreach ($categoria->productos as $producto)
                                @php
                                    $imagen = $producto->imagenes->first();
                                    $src = $imagen ? Storage::url($imagen->url) : asset('img/placeholder-producto.jpg');
                                @endphp

                                <div class="producto-card">
                                    <div class="producto-imagen"
                                        onclick="abrirModal('{{ $src }}', '{{ $producto->nombre }}')"
                                        role="button" tabindex="0"
                                        aria-label="Ver imagen completa de {{ $producto->nombre }}">

                                        <img src="{{ $src }}" data-src="{{ $src }}"
                                            alt="{{ $producto->nombre }}" class="img-producto" loading="lazy"
                                            width="400" height="400"
                                            onerror="this.src='/images/placeholder.jpg'; this.alt='Imagen no disponible'">
                                    </div>

                                    <div class="producto-info">
                                        <h4 class="producto-nombre">{{ $producto->nombre }}</h4>

                                        @if ($producto->descripcion)
                                            <p class="producto-descripcion">
                                                {{ $producto->descripcion }}
                                            </p>
                                        @endif

                                        @if ($producto->precio)
                                            <p class="producto-precio">
                                                Bs. {{ number_format($producto->precio, 2) }}
                                            </p>
                                        @endif

                                        @if (!is_null($producto->stock))
                                            <p class="producto-stock">
                                                {{ $producto->stock > 0 ? 'En stock' : 'Agotado' }}
                                            </p>
                                        @endif

                                        {{-- Botón de WhatsApp --}}
                                        @if ($whatsNumber)
                                            <button type="button" class="producto-whatsapp"
                                                onclick="abrirConsulta('{{ $src }}', '{{ $producto->nombre }}', '{{ $producto->id }}')">
                                                Contactar por WhatsApp
                                            </button>
                                        @endif
                                        <button type="button" class="producto-compartir"
                                            data-url="{{ request()->url() }}#prod-{{ $producto->slug }}"
                                            data-titulo="{{ $producto->nombre }}"
                                            data-descripcion="{{ Str::limit($producto->descripcion, 100) }}"
                                            data-imagen="{{ $src }}" onclick="compartirProducto(this)">
                                            Compartir Producto
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            <!-- Modal de consulta -->
                            <div id="modalImagen" class="modal">
                                <span class="cerrar-modal" onclick="cerrarModal()">&times;</span>
                                <img class="modal-contenido" id="imagenModal">
                                <div id="tituloModal" class="modal-titulo"></div>
                            </div>
                        </div>
                    @else
                        <p class="sin-productos">No hay productos en esta categoría</p>
                    @endif
                </section>
            @endforeach
            <div id="consultaModal" class="modal-overlay" style="display: none;">
                <div class="modal-content">
                    <button class="modal-close" onclick="cerrarConsulta()">&times;</button>

                    <h2 id="modalProductoNombre">Consultar producto</h2>
                    <img id="modalProductoImagen" src="" alt="" class="modal-imagen">

                    <form id="consultaForm" method="POST" target="_blank" action=""
                        onsubmit="setTimeout(() => cerrarConsulta(), 100)">
                        @csrf
                        <input type="hidden" id="productoId" name="producto_id">

                        <div class="form-group">
                            <label for="nombre">Nombre (opcional)</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>

                        <div class="form-group"> <label for="telefono">Teléfono (opcional)</label>
                            <input type="tel" name="telefono" id="telefono" class="form-control"
                                pattern="^\+?[0-9]{7,15}$" maxlength="15" placeholder="Ej: +59112345678">
                            <small class="form-text text-muted">
                                Ingrese un número válido (7–15 dígitos, opcionalmente con +). </small>
                        </div>

                        <div class="form-group">
                            <label for="mensaje">Mensaje</label>
                            <textarea name="mensaje" id="mensaje" class="form-control" required
                                placeholder="Hola, me interesa el producto..."></textarea>
                        </div>

                        <button type="submit" class="btn-enviar">Enviar por WhatsApp</button>
                    </form>
                </div>
            </div>
        @else
            <div class="sin-categorias">
                <p>No hay categorías disponibles en este momento.</p>
            </div>
        @endif
        <section id="mapa" class="mapa">
            <div class="container">
                <h2 class="section-title">NUESTRA UBICACIÓN</h2>

                <div class="mapa-layout-grid">
                    <!-- Columna Izquierda: Mapa con su barra de dirección flotante -->
                    <div class="mapa-contenedor">
                        <div class="mapframe">
                            <iframe
                                src="https://www.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&hl=es&z=16&output=embed"
                                width="100%" height="450"
                                style="border:0; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1);"
                                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                                title="Mapa de ubicación">
                            </iframe>
                        </div>
                        <div class="direccion-info" style="color: {{ $contenido->background }}">
                            <i class="icono-ubicacion"></i>
                            <p>{{ $contenido->pie }}</p>
                        </div>
                    </div>

                    <!-- Columna Derecha: Bloque de Horarios Minimalista -->
                    @if (isset($horarios) && $horarios->isNotEmpty())
                        <div class="horarios-contenedor-clean">
                            <div class="horarios-clean-header">
                                <h3>Horas de Operación</h3>

                                @if (isset($estadoTienda) && $estadoTienda['texto'])
                                    <span
                                        class="tag-estado {{ $estadoTienda['abierto'] ? 'tag-abierto' : 'tag-cerrado' }}">
                                        {{ $estadoTienda['texto'] }}
                                    </span>
                                @endif
                            </div>

                            <ul class="lista-dias-clean">
                                @foreach ($horarios as $horario)
                                    @php
                                        $esHoy = \Carbon\Carbon::now('America/La_Paz')->isoweekday() == $horario->dia;
                                    @endphp
                                    <li class="fila-dia-clean {{ $esHoy ? 'hoy-resaltado-clean' : '' }}">
                                        <span class="dia-nombre-clean">{{ $horario->nombre_dia }}</span>
                                        <span class="dia-horas-clean">
                                            @if ($horario->esta_cerrado || !$horario->apertura)
                                                <span class="cerrado-clean">Cerrado</span>
                                            @else
                                                <!-- Bloque Turno 1 -->
                                                {{ \Carbon\Carbon::parse($horario->apertura)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($horario->cierre)->format('H:i') }}

                                                <!-- Bloque Turno 2 (Si existe) -->
                                                @if ($horario->apertura_2 && $horario->cierre_2)
                                                    <span class="separador-clean">/</span>
                                                    {{ \Carbon\Carbon::parse($horario->apertura_2)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($horario->cierre_2)->format('H:i') }}
                                                @endif
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
    </div>
    <script src="{{ asset('dinamico/catalogo.js') }}?v={{ filemtime(public_path('dinamico/catalogo.js')) }}"></script>
     <script src="{{ asset('dinamico/compartir.js') }}?v={{ filemtime(public_path('dinamico/compartir.js')) }}"></script>
</x-layouts.plantillacatalogo>
