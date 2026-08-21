@php
    $bgColor = $contenido->background ?? '#ffffff';
    $textColor = $contenido->ctexto ?? '#333333';
    $colsec = $contenido->colsecond ?? '#333333';
    $whatsNumber = Str::of($contenido->phone ?? '')
        ->replaceMatches('/\D+/', '')
        ->__toString();
    $logoUrl = $contenido->logo_url ? '/storage/' . $contenido->logo_url : null;
    $bannerUrl = $contenido->banner_url ? '/storage/' . $contenido->banner_url : null;
    $totalProyectos = $portfolios->count();
    $proyectosActivos = $portfolios->where('estado', 'activo')->count();
@endphp
<x-layouts.plantillaportfolio :titulo="$tituloSEO ?? $titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg ?? $logoUrl"
    :locale="$locale" :backgroud="$bgColor" :icono="$logoUrl" :ogUrl="$ogUrl" :ogType="$ogType" :contenido="$contenido"
    :portfolios="$portfolios">
    <style>
        :root {
            --brand-background: {{ $bgColor }};
            --brand-text: {{ $textColor }};
            --brand-secondary: {{ $colsec }};
        }
    </style>

    <link rel="stylesheet"
        href="{{ asset('estilo/portfolio.css') }}?v={{ filemtime(public_path('estilo/portfolio.css')) }}">

    <!-- Header/Navegación -->
    <header class="portfolio-header">
        <div class="header-container">
            @if ($logoUrl)
                <div class="logo" style="display: flex; align-items: center; gap: 1rem;">
                    <img src="{{ $logoUrl }}" alt="{{ $titulo }}"
                        style="width: 40px; height: 40px; border-radius: 50%;">
                    <span>{{ $titulo }}</span>
                </div>
            @else
                <div class="logo">
                    {{ $titulo }}
                </div>
            @endif
            <nav class="nav-menu">
                <a href="#inicio" class="nav-link">Inicio</a>
                <a href="#proyectos" class="nav-link">Proyectos</a>
                <a href="#sobre-mi" class="nav-link">Sobre mí</a>
                <a href="#contacto" class="nav-link">Contacto</a>
                @if ($whatsNumber)
                    <a href="https://wa.me/{{ $whatsNumber }}" target="_blank" class="nav-link"
                        style="color: #25D366;">
                        <span>💬</span> WhatsApp
                    </a>
                @endif
            </nav>
        </div>
    </header>
    <!-- Contenido principal -->
    <main class="main-content">
        <section id="inicio" class="hero-with-logo">
            @if ($bannerUrl)
                <div class="hero-background">
                    <img src="{{ $bannerUrl }}" alt="{{ $titulo }}">
                </div>
            @endif
            <div style="position: relative; z-index: 1; width: 100%;">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $titulo }}" class="hero-logo">
                @endif

                <h1 class="hero-title">{{ $titulo }}</h1>
                <h2 class="hero-subtitle"
                    style="text-align: center; color: var(--brand-text); opacity: 0.9; font-size: 1.5rem; margin-top: 0.5rem;">
                    {{ $contenido->subtitulo_hero ?? '' }}
                </h2>

                <div style="margin-top: 2rem;">

                    @if ($whatsNumber)
                        <a href="https://wa.me/{{ $whatsNumber }}" target="_blank" class="whatsapp-button">
                            <span>💬</span> Contactar por WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </section>
        <!-- Videos -->
        <section class="portfolio-videos py-5">
            <div class="container">
                <h2 class="text-center mb-5 section-title" style="color:{{ $colsec }}">Videos del Portfolio</h2>

                @if (isset($videoportfolio) && $videoportfolio->count() > 0)
                    <div class="hero-visual shadow-lg rounded-3">
                        @include('partials.reproductor-vid', ['videos' => $videoportfolio])
                    </div>
                @else
                    {{-- Estado cuando no hay videos --}}
                    <div class="no-videos text-center py-5">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-video-slash fa-4x text-muted"></i>
                        </div>
                        <h3 class="text-muted mb-3">No hay videos disponibles</h3>
                        <p class="text-muted">Este portfolio no contiene videos por el momento.</p>
                    </div>
                @endif
            </div>
        </section>
        <!-- Proyectos -->
        <section id="proyectos" class="projects-section">
            <div class="container">
                <h2 class="text-center mb-4">Mis Trabajos</h2>

                @if ($portfolios->count() > 0)
                    <div class="projects-grid">
                        @foreach ($portfolios as $portfolio)
                            @php
                                $encryptedId = Crypt::encrypt($portfolio->id);
                                $enlace = route('verportfolio', $encryptedId);
                            @endphp

                            <a href="{{ $enlace }}" class="project-card-link">
                                <div class="project-card fade-in">
                                    @if ($portfolio->portada)
                                        <img src="{{ asset('storage/' . $portfolio->portada) }}"
                                            alt="{{ $portfolio->titulo }}" class="project-image" loading="lazy">
                                    @else
                                        <div class="project-image-placeholder">
                                            <i class="fas fa-folder-open"></i>
                                            <span>Ver Detalles</span>
                                        </div>
                                    @endif

                                    <div class="project-content">
                                        <h3 class="project-title">{{ $portfolio->titulo }}</h3>
                                        <p class="project-description">{{ $portfolio->descripcion }}</p>

                                        <div class="project-footer">
                                            @if ($portfolio->created_at)
                                                <span class="project-date">
                                                    <i class="far fa-calendar-alt"></i>
                                                    {{ $portfolio->created_at->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center" style="padding: 3rem;">
                        <p style="color: var(--brand-text); opacity: 0.7;">No hay proyectos disponibles en este momento.
                        </p>
                    </div>
                @endif
            </div>
        </section>
        <!-- Sobre mí -->
        <section id="sobre-mi" class="about-section">
            <div class="container">
                <h2 class="text-center mb-4">Sobre Mí</h2>

                <div style="max-width: 800px; margin: 0 auto;">
                    @if ($contenido->texto)
                        <div class="text-center mb-6">
                            <p>{{ $contenido->texto }}</p>
                        </div>
                    @endif
                    <!-- Redes sociales -->
                    @if (isset($redes) && $redes->count() > 0)
                        <div class="text-center">
                            <h3 style="margin-bottom: 1rem; color: var(--brand-secondary);">Mis Redes Sociales</h3>
                            <div class="social-links">
                                @foreach ($redes as $red)
                                    @php
                                        $encryptedId = Crypt::encrypt($red->id);
                                    @endphp

                                    <a href="{{ route('redireccion', $encryptedId) }}" class="social-link"
                                        target="_blank">
                                        @if ($red->image_url)
                                            <img src="{{ asset('/storage/' . $red->image_url) }}"
                                                alt="{{ $red->nombre }}" class="red-social-icon">
                                        @else
                                            <span class="red-social-text">{{ substr($red->nombre, 0, 2) }}</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <!-- Contacto -->
        <section id="contacto" class="contact-section">
            <div class="container">
                <div class="contact-header">
                    <h2 class="section-title">Contáctame</h2>
                    <p class="section-subtitle">¿Tienes un proyecto en mente? ¡Hablemos!</p>
                </div>

                <!-- Tarjeta de Ubicación (si tiene datos) -->
                @if ($contenido->pie || ($contenido->latitude && $contenido->longitude))
                    <div class="location-card">
                        <div class="location-content">
                            <div class="location-icon">
                                <i class="fas fa-map-pin"></i>
                            </div>
                            <div class="location-info">
                                <h3 class="location-title">Ubicación</h3>
                                @if ($contenido->pie)
                                    <p class="location-address">{{ $contenido->pie }}</p>
                                @endif
                                @if ($contenido->latitude && $contenido->longitude)
                                    <a href="https://maps.google.com/?q={{ $contenido->latitude }},{{ $contenido->longitude }}"
                                        target="_blank" class="location-link">
                                        <i class="fas fa-external-link-alt"></i>
                                        Ver en Google Maps
                                    </a>
                                @endif
                            </div>
                        </div>
                        @if ($contenido->latitude && $contenido->longitude)
                            <div class="location-map">
                                <iframe
                                    src="https://maps.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&z=15&output=embed"
                                    width="100%" height="250" style="border:0; border-radius: 0 0 12px 12px;"
                                    allowfullscreen loading="lazy"
                                    title="Ubicación de {{ $titulo }} en Google Maps">
                                </iframe>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Contact Info Grid -->
                <div class="contact-grid">
                    <!-- Teléfono -->
                    @if ($contenido->phone)
                        <div class="contact-card">
                            <div class="contact-card-icon" style="background: #25D36620; color: #25D366;">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-card-content">
                                <h4 class="contact-card-title">Teléfono</h4>
                                <p class="contact-card-value">{{ $contenido->phone }}</p>
                                @if ($whatsNumber)
                                    <a href="https://wa.me/{{ $whatsNumber }}" target="_blank"
                                        class="contact-card-link">
                                        <i class="fab fa-whatsapp"></i>
                                        Enviar mensaje por WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Ubicación -->
                    @if ($contenido->pie)
                        <div class="contact-card">
                            <div class="contact-card-icon" style="background: #4285F420; color: #4285F4;">
                                <i class="fas fa-location-dot"></i>
                            </div>
                            <div class="contact-card-content">
                                <h4 class="contact-card-title">Ubicación</h4>
                                <p class="contact-card-value">{{ $contenido->pie }}</p>
                                @if ($contenido->latitude && $contenido->longitude)
                                    <a href="https://maps.google.com/?q={{ $contenido->latitude }},{{ $contenido->longitude }}"
                                        target="_blank" class="contact-card-link">
                                        <i class="fas fa-external-link-alt"></i>
                                        Ver en Google Maps
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Redes Sociales -->
                    @if (isset($redes) && $redes->count() > 0)
                        <div class="contact-card contact-social">
                            <div class="contact-card-icon" style="background: #E1306C20; color: #E1306C;">
                                <i class="fas fa-share-alt"></i>
                            </div>
                            <div class="contact-card-content">
                                <h4 class="contact-card-title">Redes Sociales</h4>
                                <div class="social-icons">
                                    @foreach ($redes as $red)
                                        @php
                                            $encryptedId = Crypt::encrypt($red->id);
                                        @endphp
                                        <a href="{{ route('redireccion', $encryptedId) }}" target="_blank"
                                            class="social-icon-link" title="{{ $red->nombre }}">
                                            @if ($red->image_url)
                                                <img src="{{ asset('/storage/' . $red->image_url) }}"
                                                    alt="{{ $red->nombre }}" class="social-icon-img">
                                            @else
                                                <span class="social-icon-text">{{ substr($red->nombre, 0, 2) }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Botón WhatsApp -->
                @if ($whatsNumber)
                    <div class="contact-cta">
                        <a href="https://wa.me/{{ $whatsNumber }}" target="_blank" class="whatsapp-button">
                            <i class="fab fa-whatsapp"></i>
                            Contactar por WhatsApp
                            <span class="whatsapp-badge">💬</span>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </main>
    <!-- Footer personalizado -->
    <footer class="custom-footer">
        <div class="container">
            @if ($logoUrl)
                <div style="margin-bottom: 2rem;">
                    <img src="{{ $logoUrl }}" alt="{{ $titulo }}"
                        style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--brand-background);">
                </div>
            @endif
            <p class="footer-brand-title"
                style="color: var(--brand-background); font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">
                {{ $titulo }}
            </p>
            @if ($contenido->pie)
                <p style=" opacity: 0.9;font-size:1.2rem;">
                    {{ $contenido->pie }}
                </p>
            @endif
            @if ($contenido->phone)
                <p style="opacity: 0.9;">
                    {{ $contenido->phone }}
                </p>
            @endif
        </div>
    </footer>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-layouts.plantillaportfolio>
