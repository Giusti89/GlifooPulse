<x-layouts.principal 
    titulo="Pulse" 
    url="{{ asset('estilo/inicio.css') }}?v={{ filemtime(public_path('estilo/inicio.css')) }}">
    {{-- 1. Hero --}}
    <section class="hero">
        <div class="hero-content">
            <h1>Glifoo Pulse: tus landing pages y catálogos digitales en minutos</h1>
            <p>Crea páginas de destino optimizadas, catálogos interactivos y gestiona todo desde nuestro panel
                Administrativo.</p>
            <div class="hero-buttons">
                <a href="{{ route('planes') }}" class="btn btn-primary">Comenzar</a>                
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('./img/logos/Boton.webp') }}" alt="Vista de Glifoo Pulse">          
        </div>
    </section>

    {{-- 2. Características --}}
    <section class="features">
        <div class="feature-card">
            <i class="icon-landing"></i>
            <h3>Landing pages</h3>
            <p>Diseña páginas de aterrizaje responsive, con formularios y seguimiento.</p>
        </div>
        <div class="feature-card">
            <i class="icon-catalog"></i>
            <h3>Catálogos digitales</h3>
            <p>Publica tu catálogo de productos con galerías, filtros y contacto directo.</p>
        </div>
        <div class="feature-card">
            <i class="icon-dashboard"></i>
            <h3>Panel administrativo</h3>
            <p>Controla contenido, estadísticas y suscripciones desde un único dashboard.</p>
        </div>
        <div class="feature-card">
            <i class="icon-analytics"></i>
            <h3>Integraciones y Analíticas</h3>
            <p>Conecta con Google Analytics, WhatsApp y redes sociales.</p>
        </div>
    </section>

    {{-- 3. Estadísticas dinámicas --}}
    <section class="stats">
        <div class="stat-card">
            <h3>{{ number_format($totalLanding) }}</h3>
            <p>Landing pages creadas</p>
        </div>
        <div class="stat-card">
            <h3>{{ number_format($totalCatalogos) }}</h3>
            <p>Productos en catálogos</p>
        </div>
        <div class="stat-card">
            <h3>{{ number_format($totalClientes) }}</h3>
            <p>Clientes activos</p>
        </div>
    </section>

    {{-- 4. Clientes destacados --}}
    <section class="clientes-logos">
        <h2>Nuestros clientes</h2>
        <div class="logos-grid">
            @forelse($clientesActivos as $spot)
                <div class="logo-item">
                    <img src="{{ Storage::url($spot->contenido->logo_url) }}" alt="{{ $spot->titulo }}">
                </div>
            @empty
                <p>No hay clientes aún. ¡Tu logo aquí!</p>
            @endforelse
        </div>
    </section>   

    {{-- 6. Planes y precios + publicidad --}}
    <section class="pricing">
        <h2>Planes y Precios</h2>
        <div class="plans-grid">
            <div class="plan-card">
                <h3>Gratis</h3>
                <p>$0 / mes</p>
                <ul>
                    <li>1 landing</li>
                    <li>Sin anuncios</li>
                </ul>
                {{-- <a href="{{ route('register') }}" class="btn btn-outline">Comenzar</a> --}}
            </div>
            <div class="plan-card popular">
                <h3>Básico</h3>
                <p>$9.99 / mes</p>
                <ul>
                    <li>10 landings</li>
                    <li>Catálogos ilimitados</li>
                    <li>Soporte por email</li>
                </ul>
                {{-- <a href="{{ route('register') }}" class="btn btn-primary">Contratar</a> --}}
            </div>
            <div class="plan-card">
                <h3>Pro</h3>
                <p>$19.99 / mes</p>
                <ul>
                    <li>Landings & catálogos ilimitados</li>
                    <li>Integraciones avanzadas</li>
                    <li>Soporte prioritario</li>
                </ul>
                {{-- <a href="{{ route('register') }}" class="btn btn-outline">Contratar</a> --}}
            </div>
        </div>

        {{-- <div class="publicidad">
            <!-- Banner 300x250 -->
            <p>Espacio publicitario 300×250</p>
        </div> --}}
    </section>

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

@section('js')
    {{-- jQuery si lo necesitas --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
