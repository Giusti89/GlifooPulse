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
     <link rel="stylesheet"
         href="{{ asset('estilo/ejecutivo.css') }}?v={{ filemtime(public_path('estilo/ejecutivo.css')) }}">
     <div class="catalogo-ejecutivo">
         <!-- Header Ejecutivo -->
         <header class="ejecutivo-header">
             <div class="container">
                 <div class="header-content">
                     <div class="brand-section">
                         <img src="{{ asset('/storage/' . $contenido->logo_url) }}" alt="{{ $titulo }}"
                             class="brand-logo">
                         <div class="brand-info">
                             <h1 class="company-name">{{ $titulo }}</h1>
                         </div>
                     </div>

                     @if ($whatsNumber)
                         <div class="contact-section">
                             <a href="https://wa.me/{{ $whatsNumber }}" class="contact-button" target="_blank">
                                 <span class="contact-icon">📱</span>
                                 <span class="contact-text">
                                     <strong>Contacto Ejecutivo</strong>
                                     <small>{{ $contenido->phone ?? '' }}</small>
                                 </span>
                             </a>
                         </div>
                     @endif
                 </div>
             </div>
         </header>

         <!-- Sección Hero -->
         <section class="hero-section">
             <div class="container">
                 <div class="hero-content">
                     <div class="hero-text">
                         <h2 class="hero-title">{{ $titulo }}</h2>
                         <p class="hero-description">
                             {{ $contenido->texto ?? '' }}
                         </p>
                     </div>
                     @if (isset($videos) && $videos->count() > 0)
                         <div class="hero-visual">
                             @include('partials.reproductor-videos', ['videos' => $videos])
                         </div>
                     @elseif ($contenido->banner_url ?? false)
                         <div class="hero-visual">
                             <img src="/storage/{{ $contenido->banner_url }}" alt="Banner" class="hero-image">
                         </div>
                     @endif
                 </div>
             </div>
         </section>

         <!-- Sección Catálogo/Servicios Ejecutivos -->
         <section id="catalogo" class="servicios-ejecutivos">
             <div class="container">
                 <div class="section-header">
                     <h2 class="section-title">PORTAFOLIO DE SERVICIOS</h2>
                     <p class="section-subtitle">Soluciones especializadas para necesidades empresariales</p>
                 </div>

                 <!-- Navegación por categorías - Versión Ejecutiva -->
                 <div class="categorias-navegacion">
                     <div class="nav-scroll-container">
                         <div class="scroll-indicator left" id="scrollLeft">‹</div>
                         <div class="categorias-lista" id="stickyTabs">
                             @foreach ($categoriapro as $categoria)
                                 <button class="categoria-tab {{ $loop->first ? 'active' : '' }}"
                                     data-tab="categoria-{{ $categoria->id }}">
                                     <span class="tab-icon">⚙️</span>
                                     <span class="tab-text">{{ $categoria->nombre }}</span>
                                 </button>
                             @endforeach
                         </div>
                         <div class="scroll-indicator right" id="scrollRight">›</div>
                     </div>
                 </div>


                 <div class="categorias-contenido">
                     @foreach ($categoriapro as $categoria)
                         <div class="categoria-content {{ $loop->first ? 'active' : '' }}"
                             id="categoria-{{ $categoria->id }}">
                             <div class="categoria-header">
                                 <h3 class="categoria-titulo">{{ $categoria->nombre }}</h3>
                                 @if ($categoria->descripcion)
                                     <p class="categoria-descripcion">{{ $categoria->descripcion }}</p>
                                 @endif
                             </div>

                             <div class="servicios-grid">
                                 @foreach ($categoria->productos as $producto)
                                     @php
                                         $imagen = $producto->imagenes->first();
                                         $src = $imagen
                                             ? Storage::url($imagen->url)
                                             : asset('img/placeholder-producto.jpg');
                                     @endphp

                                     <div class="servicio-card">
                                         <div class="servicio-imagen-contenedor">
                                             <img src="{{ $src }}" data-src="{{ $src }}"
                                                 alt="{{ $producto->nombre }}" class="servicio-imagen" loading="lazy"
                                                 width="400" height="300"
                                                 onerror="this.src='/images/placeholder.jpg'; this.alt='Imagen no disponible'">
                                             @if ($producto->precio > 0)
                                                 <div class="servicio-overlay">
                                                     <button class="btn-ver-detalles"
                                                         onclick="abrirModal('{{ $src }}', '{{ $producto->nombre }}')">
                                                         Ver detalles
                                                     </button>
                                                 </div>
                                             @endif
                                         </div>

                                         <div class="servicio-info">
                                             <h4 class="servicio-nombre">{{ $producto->nombre }}</h4>
                                             <p class="servicio-descripcion">
                                                 {{ Str::limit($producto->descripcion, 1200) }}</p>

                                             @if ($producto->precio > 0)
                                                 <div class="servicio-precio">
                                                     <span class="precio-label">Costo:</span>
                                                     <span
                                                         class="precio-valor">${{ number_format($producto->precio, 2) }}</span>
                                                 </div>
                                             @endif

                                             <div class="servicio-actions">
                                                 <button type="button" class="btn-consultor"
                                                     onclick="abrirConsulta('{{ $src }}', '{{ $producto->nombre }}', '{{ $whatsNumber }}')">
                                                     <span class="btn-icon">💬</span>
                                                     Realiza tu consulta
                                                 </button>
                                                 @if ($producto->precio > 0)
                                                     <span
                                                         class="servicio-estado estado-{{ Str::slug($producto->estado) }}">
                                                         {{ $producto->estado }}
                                                     </span>
                                                 @endif
                                             </div>
                                         </div>
                                     </div>
                                 @endforeach
                             </div>
                         </div>
                     @endforeach
                 </div>
             </div>
         </section>
         <!------------------ Servicios-------------->
         <!------------------Enlaces y redes sociales-------------->
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
         <!------------------Enlaces y redes sociales-------------->
         <!-- Mapa Ejecutivo -->
         <section id="mapa" class="mapa-ejecutivo">
             <div class="container">
                 <div class="section-header">
                     <h2 class="section-title">UBICACIÓN CORPORATIVA</h2>
                     <p class="section-subtitle">Encuentre nuestras instalaciones principales</p>
                 </div>

                 <div class="mapa-contenedor-ejecutivo">
                     <div class="mapframe-ejecutivo">
                         <iframe
                             src="https://www.google.com/maps?q={{ $contenido->latitude }},{{ $contenido->longitude }}&hl=es&z=16&output=embed"
                             width="100%" height="450" style="border: 0;" allowfullscreen loading="lazy"
                             referrerpolicy="no-referrer-when-downgrade" title="Mapa de ubicación corporativa">
                         </iframe>
                     </div>

                     <div class="info-corporativa">
                         <div class="info-card">
                             <div class="info-icon">📍</div>
                             <div class="info-content">
                                 <h4>Dirección Principal</h4>
                                 <p style="color: {{ $bgColor }}">{{ $contenido->pie }}</p>
                             </div>
                         </div>

                         @if ($contenido->horario ?? false)
                             <div class="info-card">
                                 <div class="info-icon">🕒</div>
                                 <div class="info-content">
                                     <h4>Horario Corporativo</h4>
                                     <p>{{ $contenido->horario }}</p>
                                 </div>
                             </div>
                         @endif

                         @if ($whatsNumber)
                             <div class="info-card">
                                 <div class="info-icon">📞</div>
                                 <div class="info-content">
                                     <h4>Contacto Directo</h4>
                                     <p>{{ $contenido->phone ?? '' }}</p>
                                     <a href="https://wa.me/{{ $whatsNumber }}" class="contact-link"
                                         target="_blank">
                                         Agenda una reunión
                                     </a>
                                 </div>
                             </div>
                         @endif
                     </div>
                 </div>
             </div>
         </section>

         <!-- Footer Ejecutivo -->
         <footer class="ejecutivo-footer">
             <div class="container">
                 <div class="footer-content">
                     <div class="footer-brand">
                         <img src="/storage/{{ $contenido->logo_url }}" alt="{{ $titulo }}"
                             class="footer-logo">
                         <p class="footer-descripcion">
                             {{ $contenido->descripcion_footer ?? 'Líderes en soluciones empresariales innovadoras' }}
                         </p>
                     </div>

                     <div class="footer-contact">
                         <h4>Contacto</h4>
                         @if ($whatsNumber)
                             <p><strong>Teléfono:</strong> {{ $contenido->phone ?? '' }}</p>
                         @endif
                         @if ($contenido->email ?? false)
                             <p><strong>Email:</strong> {{ $contenido->email }}</p>
                         @endif
                         @if ($contenido->pie ?? false)
                             <p><strong>Dirección:</strong> {{ $contenido->pie }}</p>
                         @endif
                     </div>
                 </div>
             </div>
         </footer>
     </div>
     <script src="{{ asset('./dinamico/ejecutivo.js') }}?v={{ filemtime(public_path('./dinamico/ejecutivo.js')) }}">
     </script>
 </x-layouts.plantillacatalogo>
 <div id="imagenModal" class="modal-ejecutivo">
     <div class="modal-contenido">
         <span class="modal-cerrar">&times;</span>
         <img id="modalImagen" src="" alt="">
         <div id="modalTitulo" class="modal-titulo"></div>
     </div>
 </div>
