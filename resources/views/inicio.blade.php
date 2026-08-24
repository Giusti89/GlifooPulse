<x-layouts.principal titulo="Pulse"
    url="{{ asset('estilo/inicio.css') }}?v={{ filemtime(public_path('estilo/inicio.css')) }}">
    {{-- 1. Hero --}}
    <!-- ===== HERO SECTION ===== -->
    <section class="hero" aria-labelledby="hero-title">
        <div class="hero-content glass-effect">
            <div class="contenido-inicial">
                <h1 id="hero-title">Crea Catálogos Digitales y Portafolios con Glifoo Pulse</h1>

                <!-- ✅ SUBTÍTULO DESCRIPTIVO -->
                <p class="hero-subtitle">
                    La plataforma para diseñar landing pages optimizadas,
                    catálogos interactivos y portafolios profesionales.
                    <strong>Sin necesidad de saber programar</strong>.
                </p>

                <!-- ✅ BOTONES CON TEXTO DESCRIPTIVO -->
                <div class="hero-buttons">
                    <a href="{{ route('planes') }}" class="btn btn-primary">
                        Comenzar ahora
                        <span class="btn-icon">→</span>
                    </a>
                    <a href="#features" class="btn btn-secondary">
                        Ver características
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Características --}}
    <!-- ===== CARACTERÍSTICAS ===== -->
    <section class="features" id="features" aria-labelledby="features-title">
        <div class="titulo">
            <h2 id="features-title">Todo lo que necesitas para crecer</h2>
            <p>Herramientas poderosas diseñadas para impulsar tu productividad</p>
        </div>

        <div class="cartas">
            <article class="feature-card">
                <div class="card-icon" aria-hidden="true">📱</div>
                <h3 class="card-title">Glifoo Bio link</h3>
                <p class="card-description">Diseña landing pages responsive con métricas de seguimiento y enlaces
                    personalizados.</p>
            </article>

            <article class="feature-card">
                <div class="card-icon" aria-hidden="true">📦</div>
                <h3 class="card-title">Catálogos digitales</h3>
                <p class="card-description">Publica tu catálogo de productos con galerías, filtros y contacto directo
                    con clientes.</p>
            </article>

            <article class="feature-card">
                <div class="card-icon" aria-hidden="true">🎨</div>
                <h3 class="card-title">Portfolio</h3>
                <p class="card-description">Muestra al mundo tu experiencia y habilidades con fotos y videos
                    profesionales.</p>
            </article>

            <article class="feature-card">
                <div class="card-icon" aria-hidden="true">⚙️</div>
                <h3 class="card-title">Panel administrativo</h3>
                <p class="card-description">Controla contenidos, estadísticas y suscripciones desde un solo dashboard
                    intuitivo.</p>
            </article>

            <article class="feature-card">
                <div class="card-icon" aria-hidden="true">🔒</div>
                <h3 class="card-title">Seguridad Total</h3>
                <p class="card-description">Protección de datos con encriptación end-to-end y certificados SSL
                    incluidos.</p>
            </article>

            <article class="feature-card">
                <div class="card-icon" aria-hidden="true">📊</div>
                <h3 class="card-title">Reportes avanzados</h3>
                <p class="card-description">Visualiza el progreso con dashboards en tiempo real para tomar las mejores
                    decisiones.</p>
            </article>
        </div>
    </section>


    {{-- 6. Planes y precios + publicidad --}}
    <section class="pricing">
        <div class="glass-effectb">
            <div class="titulo">
                <h2>Precios simples y transparentes</h2>
                <p>Elige el plan perfecto para ti</p>
            </div>
            <div class="plans-container" id="plans-container">
                @foreach ($paquetes as $index => $paquete)
                    <div class="cajaplanes plan-item" data-id="{{ $paquete->id }}"
                        data-id-encrypted="{{ Crypt::encrypt($paquete->id) }}"
                        data-descripcion='@json($paquete->descripcion)' data-index="{{ $index }}"
                        data-marco="{{ $paquete->marco }}">
                        <div class="planes" style=" border: 3px solid {{ $paquete->marco }};">
                            <h2 style="font-family: Cocogoose;">{{ $paquete->nombre }}</h2>
                            <h3>Bs. {{ $paquete->precio }} / Mes</h3>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="caracteristicas">
                <div class="cajacaracteristicas" id="caracteristicas-box"
                    style="--color-marco: {{ $paquete->marco }};">
                    <div class="caract-grid">
                        <div class="caract-descripcion">
                            <h3 id="titulo-plan"></h3>
                            <div id="texto-plan" class="tarjeta__descripcion"></div>

                            <a id="btn-detalles" href="#" class="btn-detalles-plan">
                                REGÍSTRATE
                            </a>
                        </div>
                        <div class="caract-lista" id="lista-caracteristicas">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    {{-- <div class="publicidad">
            <!-- Banner 300x250 -->
            <p>Espacio publicitario 300×250</p>
        </div> --}}


    {{-- 7. Blog / noticias --}}
    {{-- <section class="blog-posts">
        <h2>Últimos artículos</h2>
        <div class="posts-grid">
            @forelse($ultimosPosts as $post)
                <article class="post-card">
                    <h3>{{ $post->titulo }}</h3>
                    <p>{{ Str::limit($post->extracto, 100) }}</p>
                    <a href="{{ route('blog.show', $post) }}" class="read-more">Leer más</a>
                </article>
            @empty
                <p>Próximamente: consejos de marketing digital.</p>
            @endforelse
        </div>
    </section> --}}

    {{-- 8. Espacio lateral de ads (si tu layout lo permite) --}}
    {{-- <aside class="ad-space">
        <p>Espacio publicitario 728×90</p>
    </aside> --}}
</x-layouts.principal>
<script src="{{ asset('dinamico/planes.js') }}?v={{ filemtime(public_path('dinamico/planes.js')) }}"></script>
@section('js')
    {{-- jQuery si lo necesitas --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
