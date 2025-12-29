@php
    // Variables CSS dinámicas
    $bgColor = $contenido->background ?? '#ffffff';
    $textColor = $contenido->ctexto ?? '#333333';
    $colsec = $contenido->colsecond ?? '#333333';

    // Formatear número de WhatsApp
    $whatsNumber = Str::of($contenido->phone ?? '')
        ->replaceMatches('/\D+/', '')
        ->__toString();

    // Extraer datos del contenido
    // Extraer datos
    $logoUrl = $contenido->logo_url ? '/storage/' . $contenido->logo_url : null;
    $bannerUrl = $contenido->banner_url ? '/storage/' . $contenido->banner_url : null;

    // Estadísticas
    $totalProyectos = $portfolios->count();
    $proyectosActivos = $portfolios->where('estado', 'activo')->count();
@endphp
<x-layouts.plantillaportfolio :titulo="$tituloSEO ?? $titulo" :descripcion="$descripcionSEO" :keywords="$keywordsSEO" :robots="$robots" :imagenOg="$imagenOg ?? $logoUrl"
    :locale="$locale" :backgroud="$bgColor" :icono="$logoUrl">
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
                <h2 class="text-center mb-4">Mis Proyectos</h2>

                @if ($portfolios->count() > 0)
                    <div class="projects-grid">
                        @foreach ($portfolios as $portfolio)
                            <div class="project-card fade-in">
                                @if ($portfolio->portada)
                                    <img src="{{ asset('storage/' . $portfolio->portada) }}"
                                        alt="{{ $portfolio->titulo }}" class="project-image">
                                @else
                                    <div
                                        style="height: 250px; background: var(--brand-secondary); display: flex; align-items: center; justify-content: center;">
                                        <span
                                            style="color: var(--brand-background); font-weight: 600;">{{ $portfolio->titulo }}</span>
                                    </div>
                                @endif
                                <div class="project-content">
                                    <h3 class="project-title">{{ $portfolio->titulo }}</h3>
                                    <p class="project-description">{{ $portfolio->descripcion }}</p>

                                    @php
                                        $encryptedId = Crypt::encrypt($portfolio->id);
                                    @endphp
                                    <x-layouts.btnenviodat class="modificar" rutaEnvio="verportfolio"
                                        dato="{{ $encryptedId }}" nombre="Ver Porfolio" color="{{ $bgColor }}"
                                        colort="{{ $textColor }}">
                                    </x-layouts.btnenviodat>
                                    @if ($portfolio->created_at)
                                        <div
                                            style="font-size: 0.85rem; color: var(--brand-text); opacity: 0.7; margin-top: 0.5rem;">
                                            Creado: {{ $portfolio->created_at->format('d/m/Y') }}
                                        </div>
                                    @endif

                                </div>
                            </div>
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
                <h2 class="text-center mb-4">Contáctame</h2>
                @if ($contenido->pie)
                    <div class="location-card" style="max-width: 600px; margin: 0 auto 3rem;">
                        <div
                            style="display: flex; align-items: center; gap: 1rem; background: var(--brand-secondary); color: var(--brand-background); padding: 1.5rem; border-radius: 12px;">
                            <div style="font-size: 2rem;">📍</div>
                            <div>
                                <div style="font-weight: 600; font-size: 1.25rem;">Ubicación</div>
                                <div style="opacity: 0.9;">{{ $contenido->pie }}</div>

                                @if ($contenido->latitude && $contenido->longitude)
                                    <div style="margin-top: 1rem;">
                                        <iframe
                                            src="https://maps.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&z=15&output=embed"
                                            width="100%" height="200" style="border:0; border-radius: 8px;"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @elseif($contenido->show_map)
                                    <div style="margin-top: 1rem;">
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($contenido->pie) }}"
                                            target="_blank"
                                            style="display: inline-block; background: var(--brand-background); color: var(--brand-secondary); padding: 0.5rem 1.5rem; border-radius: 50px; font-weight: 600; text-decoration: none;">
                                            Ver en Google Maps
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div style="max-width: 800px; margin: 0 auto;">
                    <div class="contact-info">
                        @if ($contenido->phone)
                            <div class="contact-item">
                                <div class="contact-icon">📱</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--brand-secondary);">
                                        {{ $contenido->phone }}
                                    </div>
                                    @if ($whatsNumber)
                                        <a href="https://wa.me/{{ $whatsNumber }}" target="_blank"
                                            style="color: var(--brand-secondary); font-size: 0.9rem;">
                                            Enviar mensaje por WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($contenido->pie)
                            <div class="contact-item">
                                <div class="contact-icon">📍</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--brand-secondary);">Ubicación</div>
                                    <div style="color: var(--brand-secondary); opacity: 0.8;">{{ $contenido->pie }}
                                    </div>
                                    @if ($contenido->latitude && $contenido->longitude)
                                        <a href="https://maps.google.com/?q={{ $contenido->latitude }},{{ $contenido->longitude }}"
                                            target="_blank" style="color: var(--brand-secondary); font-size: 0.9rem;">
                                            Ver en Google Maps
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if (isset($redes) && $redes->count() > 0)
                            <div class="contact-item">
                                <div class="contact-icon">🔗</div>
                                <div>
                                    <div style="font-weight: 600; color: var(--brand-secondary);">Redes Sociales</div>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
                                        @foreach ($redes as $red)
                                            @php
                                                $encryptedId = Crypt::encrypt($red->id);
                                            @endphp

                                            <a href="{{ route('redireccion', $encryptedId) }}"
                                                style="color: var(--brand-secondary); text-decoration: none; padding: 0.25rem 0.75rem; border: 1px solid var(--brand-secondary); border-radius: 4px;"
                                                class="link-redess"target="_blank">
                                                @if ($red->image_url)
                                                    <img src="{{ asset('/storage/' . $red->image_url) }}"
                                                        alt="{{ $red->nombre }}" class="red-social-icon">
                                                @else
                                                    <span
                                                        class="red-social-text">{{ substr($red->nombre, 0, 2) }}</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="text-center" style="margin-top: 3rem;">
                        @if ($whatsNumber)
                            <a href="https://wa.me/{{ $whatsNumber }}" target="_blank" class="whatsapp-button">
                                <span>💬</span> Contactar por WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
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

            <h3 style="margin-bottom: 1rem;">{{ $titulo }}</h3>

            @if ($contenido->pie)
                <p style="margin-bottom: 1.5rem; opacity: 0.9;">
                    <span>📍</span> {{ $contenido->pie }}
                </p>
            @endif

            @if ($contenido->phone)
                <p style="margin-bottom: 1.5rem; opacity: 0.9;">
                    <span>📱</span> {{ $contenido->phone }}
                </p>
            @endif

            @if (isset($redes) && $redes->count() > 0)
                <div class="social-links">
                    @foreach ($redes as $red)
                        @php
                            $encryptedId = Crypt::encrypt($red->id);
                        @endphp

                        <a href="{{ route('redireccion', $encryptedId) }}" class="social-link" target="_blank">
                            @if ($red->image_url)
                                <img src="{{ asset('/storage/' . $red->image_url) }}" alt="{{ $red->nombre }}"
                                    class="red-social-icon">
                            @else
                                <span class="red-social-text">{{ substr($red->nombre, 0, 2) }}</span>
                            @endif
                        </a>
                    @endforeach

                </div>
            @endif


        </div>
    </footer>

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</x-layouts.plantillaportfolio>
