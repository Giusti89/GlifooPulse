@php
    // Captura del estado del formulario en tiempo real desde Livewire
    $state = $getLivewire()->data;
    
    // Mapeo exacto con los nombres de variables del portafolio.pdf
    $bgColor = $state['background'] ?? '#ffffff';
    $colsec = $state['colsecond'] ?? '#333333';
    $textColor = $state['ctexto'] ?? '#333333';
    
    $bgsecundario = $state['fondocolor'] ?? $bgColor;
    $textsubtitulo = $state['text'] ?? $colsec;
    $textdescrip = $state['secondary'] ?? $textColor;
    
    $botonfondo = $state['primary_button'] ?? $colsec;
    $botontexto = $state['button_text'] ?? '#ffffff';
    $footerfondo = $state['footer'] ?? '#333333';
@endphp

<!-- Contenedor Maqueta General del Portafolio -->
<div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-md text-xs select-none font-sans">
    
    <!-- 1. HEADER (Fondo condicionado o por defecto transparente/claro) -->
    <header class="p-3 flex justify-between items-center border-b border-gray-100 dark:border-gray-800" style="background-color: {{ $bgColor }};">
        <div class="flex items-center gap-1.5 font-bold" style="color: {{ $textColor }};">
            <div class="w-5 h-5 rounded-full bg-gray-400 flex items-center justify-center text-[9px] text-white">Logo</div>
            <span>Mi Portafolio</span>
        </div>
        <nav class="flex gap-3 font-medium" style="color: {{ $textColor }};">
            <span>Inicio</span>
            <span>Trabajos</span>
            <span style="color: {{ $colsec }}; font-weight: bold;">WhatsApp</span>
        </nav>
    </header>

    <!-- 2. MAIN CONTENT & HERO SECTION -->
    <main class="p-4 transition-colors duration-200" style="background-color: {{ $bgColor }};">
        
        <!-- Hero Section -->
        <section class="text-center py-4 mb-6">
            <h1 class="text-base font-bold mb-1" style="color: {{ $textColor }};">
                Título del Portafolio
            </h1>
            <h2 class="text-xs font-semibold mb-3" style="color: {{ $colsec }}; opacity: 0.9;">
                Subtítulo del Hero
            </h2>
            <!-- Botón WhatsApp -->
            <div class="inline-block px-4 py-1.5 rounded-full font-bold text-center text-[10px] shadow-sm" 
                 style="background-color: {{ $botonfondo }}; color: {{ $botontexto }};">
                 💬 Contactar por WhatsApp
            </div>
        </section>

        <!-- Seccion Proyectos / Mis Trabajos -->
        <section class="mb-4">
            <h2 class="text-center text-xs font-bold mb-3" style="color: {{ $colsec }};">
                Mis Trabajos
            </h2>
            
            <!-- Grid de Tarjetas (Simulando el CSS projects-grid) -->
            <div class="grid grid-cols-1 gap-3">
                <div class="rounded-lg p-3 border border-gray-100 shadow-sm transition-colors duration-200" 
                     style="background-color: {{ $bgsecundario }};">
                    
                    <!-- Imagen de muestra -->
                    <div class="w-full h-16 bg-gray-200 dark:bg-gray-800 rounded mb-2 flex items-center justify-center text-gray-400 text-[10px]">
                        [ Imagen Portada ]
                    </div>
                    
                    <!-- Textos internos de la tarjeta -->
                    <h3 class="font-bold text-xs mb-0.5" style="color: {{ $textsubtitulo }};">
                        Título del Proyecto
                    </h3>
                    <p class="text-[10px] leading-relaxed" style="color: {{ $textdescrip }};">
                        Descripción corta utilizando las variables de elementos secundarios.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- 3. CUSTOM FOOTER -->
    <footer class="p-4 text-center transition-colors duration-200" style="background-color: {{ $footerfondo }}; color: {{ $textColor }};">
        <div class="w-8 h-8 rounded-full bg-gray-500 mx-auto mb-2 border-2" style="border-color: {{ $bgColor }};"></div>
        <p class="font-semibold text-xs mb-1" style="color: {{ $textColor }};">Nombre del Portafolio</p>
        <p class="text-[9px] opacity-80">© {{ date('Y') }} - Pie de página</p>
    </footer>
</div>
