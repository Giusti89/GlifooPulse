<x-layouts.principal titulo="Pulse"
    url="{{ asset('estilo/inicio.css') }}?v={{ filemtime(public_path('estilo/inicio.css')) }}">
    {{-- 1. Hero --}}
    <section class="hero" style="background-image: url('./img/logos/bannerglifoo.jpg')">
        <div class="hero-content">
            <h1>Glifoo Pulse: tu pagina de enlaces y catálogos digitales en minutos</h1>
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
        <div class="plans-container">
            @forelse($paquetes as $paquete)
                @if ($paquete->nombre === 'Glifoo Enterprise')
                    <div class="plan-card popular">
                        <div class="cuerpoplan">
                            <div class="cabplan">
                                <h2>{{ $paquete->nombre }}</h2>
                                <h3>Bs. {{ $paquete->precio }} / Mes</h3>
                                @php
                                    $encryptedId = Crypt::encrypt($paquete->id);
                                @endphp
                                <x-layouts.btnenviodat class="modificar" rutaEnvio="registro"
                                    dato="{{ $encryptedId }}" nombre="REGISTRATE">
                                </x-layouts.btnenviodat>
                            </div>

                            <ul>
                                <li>{!! str($paquete->descripcion)->sanitizeHtml() !!}</li>
                            </ul>

                        </div>
                    </div>
                @else
                    <div class="plan-card">
                        <div class="cuerpoplan">
                            <div class="cabplan">
                                <h2>{{ $paquete->nombre }}</h2>
                                <h3>Bs. {{ $paquete->precio }} / Mes</h3>
                                @php
                                    $encryptedId = Crypt::encrypt($paquete->id);
                                @endphp
                                <x-layouts.btnenviodat class="modificar" rutaEnvio="registro"
                                    dato="{{ $encryptedId }}" nombre="REGISTRATE">
                                </x-layouts.btnenviodat>
                            </div>
                            <ul>
                                <li>{!! str($paquete->descripcion)->sanitizeHtml() !!}</li>
                            </ul>

                        </div>
                    </div>
                @endif
            @empty
                <div class="no-packages">
                    <p>No hay Servicios disponibles. ¡Tu logo aquí!</p>
                </div>
            @endforelse
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

@section('js')
    {{-- jQuery si lo necesitas --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
