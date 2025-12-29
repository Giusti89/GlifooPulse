@php
    // Colores y estilos del contenido
    $bgColor = $contenido->background ?? '#ffffff';
    $textColor = $contenido->ctexto ?? '#333333';
    $colsec = $contenido->colsecond ?? '#333333';

    $whatsNumber = Str::of($contenido->phone ?? '')
        ->replaceMatches('/\D+/', '')
        ->__toString();

    $logoUrl = $contenido->logo_url ? Storage::url($contenido->logo_url) : null;
    $bannerUrl = $contenido->banner_url ? Storage::url($contenido->banner_url) : null;
@endphp

<x-layouts.plantillacatalogo :titulo="$portfolio->titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg"
    :locale="$locale" :backgroud="$bgColor" :icono="$logoUrl">
    <style>
        :root {
            --brand-background: {{ $bgColor }};
            --brand-text: {{ $textColor }};
            --brand-secondary: {{ $colsec }};
        }
    </style>

    <link rel="stylesheet"
        href="{{ asset('estilo/portfoliogaleria.css') }}?v={{ filemtime(public_path('estilo/portfoliogaleria.css')) }}">


    <main class="portfolio-view" style="--bg-color: {{ $bgColor }}; --text-color: {{ $textColor }};">
        <div class="portfolio-container">
            <!-- Header del Portfolio -->
            <header class="portfolio-header">
                <h1 class="portfolio-title">{{ $portfolio->titulo }}</h1>
                @if ($portfolio->descripcion)
                    <p class="portfolio-description">{{ $portfolio->descripcion }}</p>
                @endif
            </header>

            <div class="portfolio-content">
                <!-- Sección izquierda: Galería de imágenes -->
                <section class="portfolio-gallery-section">
                    <div class="gallery-header">
                        <h2 class="section-title">Galería del Proyecto</h2>
                    </div>

                    @if ($imagenes->isNotEmpty())
                        <div class="masonry-gallery" id="portfolioGallery">
                            @foreach ($imagenes as $index => $foto)
                                <div class="masonry-item" data-index="{{ $index }}"
                                    data-title="{{ $foto->titulo ?? '' }}" data-desc="{{ $foto->descripcion ?? '' }}">
                                    <div class="image-container">
                                        <img src="{{ Storage::url($foto->imagen) }}"
                                            alt="{{ $foto->titulo ?? 'Imagen ' . ($index + 1) . ' del proyecto ' . $portfolio->titulo }}"
                                            class="gallery-image" loading="lazy"
                                            data-full="{{ Storage::url($foto->imagen) }}">

                                        <!-- Overlay con info de la imagen -->
                                        <div class="image-overlay">
                                            <div class="overlay-content">
                                                @if ($foto->titulo)
                                                    <h3 class="image-title">{{ $foto->titulo }}</h3>
                                                @endif
                                                @if ($foto->descripcion)
                                                    <p class="image-desc">{{ Str::limit($foto->descripcion, 150) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-gallery">
                            <i class="fas fa-images"></i>
                            <p>No hay imágenes en este portfolio</p>
                        </div>
                    @endif
                </section>

                <!-- Sección derecha: Datos técnicos -->
                <aside class="portfolio-details-section">
                    <div class="details-card" style="background:{{ $colsec }}">
                        <h2 class="details-title" style="color:{{ $bgColor }}">📋 Detalles del Proyecto</h2>

                        @if ($datosTecnicos)
                            <!-- Información del cliente -->
                            @if ($datosTecnicos->cliente)
                                <div class="detail-group">
                                    <h3 class="detail-label" style="color:{{ $colsec }}">
                                        <i class="fas fa-user-tie"></i> Cliente
                                    </h3>
                                    <p class="detail-value" style="color:var(--brand-text)">
                                        {{ $datosTecnicos->cliente }}</p>
                                </div>
                            @endif

                            <!-- Implicación/rol -->
                            @if ($datosTecnicos->implicacion)
                                <div class="detail-group">
                                    <h3 class="detail-label" style="color:{{ $colsec }}">
                                        <i class="fas fa-tasks"></i> Rol / Implicación
                                    </h3>
                                    <p class="detail-value" style="color:{{ $textColor }}">
                                        {{ $datosTecnicos->implicacion }}</p>
                                </div>
                            @endif

                            <!-- Tecnologías -->
                            @if (!empty($datosTecnicos->tecnologias))
                                <div class="detail-group">
                                    <h3 class="detail-label" style="color:{{ $colsec }}">
                                        <i class="fas fa-code"></i> Tecnologías
                                    </h3>
                                    <div class="tech-tags">
                                        @foreach ($datosTecnicos->tecnologias as $tech)
                                            <span class="tech-tag">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Enlace al proyecto -->
                            @if ($datosTecnicos->tieneEnlace())
                                <div class="detail-group">
                                    <h3 class="detail-label" style="color:{{ $bgColor }}">
                                        <i class="fas fa-external-link-alt"></i> Ver Proyecto
                                    </h3>
                                    <a href="{{ $datosTecnicos->enlace_proyecto }}" target="_blank"
                                        rel="noopener noreferrer" class="project-link">
                                        {{ parse_url($datosTecnicos->enlace_proyecto, PHP_URL_HOST) }}
                                        <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="no-details">
                                <i class="fas fa-info-circle"></i>
                                <p style="color: {{$textColor}}">No hay datos técnicos disponibles</p>
                            </div>
                        @endif

                        <!-- Información general del portfolio -->
                        <div class="detail-group">
                            <h3 class="detail-label" style="color:{{ $colsec }}">
                                <i class="fas fa-calendar"></i> Información
                            </h3>

                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label" style="color:{{ $colsec }}">Imágenes:</span>
                                    <span class="info-value"
                                        style="color:{{ $textColor }}">{{ $imagenes->count() }}</span>
                                </div>

                                @if ($portfolio->created_at)
                                    <div class="info-item">
                                        <span class="info-label" style="color:{{ $colsec }}">Creado:</span>
                                        <span class="info-value" style="color:{{ $textColor }}">
                                            {{ $portfolio->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- 🔽 Recuadro de información de imagen activa dentro de detail-group -->
                            <div class="image-info-box" id="imageInfoBar">
                                <div class="info-bar-content">
                                    <div class="current-image-info">
                                        <h4 class="current-title" id="currentImageTitle" style="color:{{ $colsec }}"></h4>
                                        <p class="current-desc" id="currentImageDesc" style="color:{{ $colsec }}"></p>
                                    </div>
                                    <div class="image-counter">
                                        <span id="currentImageIndex" style="color:{{ $textColor }}">1</span> /
                                        <span id="totalImages" style="color:{{ $textColor }}">{{ $imagenes->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modal para vista completa de imagen -->
                        <div class="image-modal" id="imageModal">
                            <div class="modal-overlay" id="modalOverlay"></div>
                            <div class="modal-content">
                                <button class="modal-close" id="modalClose">&times;</button>
                                <div class="modal-image-container">
                                    <img id="modalImage" src="" alt="">
                                    <div class="modal-image-info">
                                        <h3 id="modalImageTitle"></h3>
                                        <p id="modalImageDesc"></p>
                                    </div>
                                </div>
                                <div class="modal-navigation">
                                    <button class="nav-btn prev-btn" id="prevImage">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="nav-btn next-btn" id="nextImage">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
    </main>


    <script
        src="{{ asset('./dinamico/portfolio-gallery.js') }}?v={{ filemtime(public_path('./dinamico/portfolio-gallery.js')) }}">
    </script>
</x-layouts.plantillacatalogo>
