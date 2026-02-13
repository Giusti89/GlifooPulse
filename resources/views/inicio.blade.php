<x-layouts.principal titulo="Pulse"
    url="{{ asset('estilo/inicio.css') }}?v={{ filemtime(public_path('estilo/inicio.css')) }}">
    {{-- 1. Hero --}}
   <section class="hero relative overflow-hidden">
    {{-- Video Background --}}
    <video class="absolute top-0 left-0 w-full h-full object-cover z-0" 
           autoplay 
           loop 
           muted 
           playsinline>
        <source src="{{ asset('./img/logos/Baner.mp4') }}" type="video/mp4">
        {{-- Fallback en caso de que el video no cargue --}}
        <img src="{{ asset('./img/logos/Baner.mp4') }}" alt="Background" class="w-full h-full object-cover">
    </video>
    
    {{-- Overlay para mejorar legibilidad del contenido --}}
    <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-40 z-10"></div>
    
    {{-- Contenido --}}
    <div class="hero-content relative z-20">
        {{-- Tu contenido aquí --}}
    </div>
    
</section>

    {{-- 2. Características --}}
    <section class="features">
        <div class="feature-card">
            <i class="icon-landing"></i>
            <h3>Glifoo link tree</h3>
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
            <h3>Portfolio</h3>
            <p>Muestra al mundo tu experiencia y habilidades con fotos y videos</p>
        </div>
    </section>

    {{-- 4. Clientes destacados --}}
    <section class="clientes-logos">
        <h2>Nuestros clientes</h2>
        <div class="logos-grid">
            @forelse($clientesActivos as $spot)
                <div class="logo-item">
                    <a href="{{ route('publicidad', $spot->slug) }}" target="blank">
                        <img src="{{ Storage::url($spot->contenido->logo_url) }}" alt="{{ $spot->titulo }}">
                    </a>
                    <h2>{{ $spot->titulo }}<h2>
                </div>
            @empty
                <p>No hay clientes aún. ¡Tu logo aquí!</p>
            @endforelse
        </div>
    </section>

    {{-- 6. Planes y precios + publicidad --}}
    <section class="pricing">
        <h2>Planes y Precios</h2>
        <div class="plans-container" id="plans-container">
            @foreach ($paquetes as $index => $paquete)
                <div class="cajaplanes plan-item" data-id="{{ $paquete->id }}"
                    data-id-encrypted="{{ Crypt::encrypt($paquete->id) }}"
                    data-descripcion='@json($paquete->descripcion)' data-index="{{ $index }}"
                    data-marco="{{ $paquete->marco }}">
                    <div class="planes" style=" border: 3px solid {{ $paquete->marco }};">
                        <h2>{{ $paquete->nombre }}</h2>
                        <h3>Bs. {{ $paquete->precio }} / Mes</h3>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="caracteristicas" id="caracteristicas-box" style="--color-marco: {{ $paquete->marco }};">
            <div class="cajacaracteristicas">
                <div class="caract-grid">
                    <div class="caract-descripcion">
                        <h3 id="titulo-plan"></h3>
                        <div id="texto-plan" class="tarjeta__descripcion"></div>

                        <a id="btn-detalles" href="#" class="btn-detalles-plan">
                            REGISTRATE
                        </a>
                    </div>
                    <div class="caract-lista" id="lista-caracteristicas">
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
