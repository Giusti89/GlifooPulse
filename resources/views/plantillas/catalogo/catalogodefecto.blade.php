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
    <link rel="stylesheet" href="{{ asset('estilo/catalogob.css') }}">

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
        <h1 class="catalogo-titulo">{{ $catalogos->seo_title ?? 'Mi Catálogo' }}</h1>

        @if ($categoriapro->count() > 0)
            <!-- Navegación por categorías -->
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
                    <h2 class="categoria-titulo">{{ strtoupper($categoria->nombre) }}</h2>

                    @if ($categoria->productos->isNotEmpty())
                        <div class="productos-grid">
                            @foreach ($categoria->productos as $producto)
                                @php
                                    $imagen = $producto->imagenes->first();
                                    $src = $imagen ? Storage::url($imagen->url) : asset('img/placeholder-producto.jpg');
                                @endphp

                                <div class="producto-card">
                                    <div class="producto-imagen"
                                        onclick="abrirModal('{{ $src }}', '{{ $producto->nombre }}')">
                                        <img src="{{ $src }}" alt="{{ $producto->nombre }}"
                                            class="img-producto" loading="lazy">
                                    </div>

                                    <div class="producto-info">
                                        <h3 class="producto-nombre">{{ $producto->nombre }}</h3>

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
        @else
            <div class="sin-categorias">
                <p>No hay categorías disponibles en este momento.</p>
            </div>
        @endif
    </div>
    <script src="{{ asset('./dinamico/catalogo.js') }}"></script>
</x-layouts.plantillacatalogo>
