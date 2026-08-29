@php
    // Captura del estado del formulario en tiempo real desde Livewire
    $state = $getLivewire()->data;
    
    // Mapeo idéntico al stylesheet ejecutivo.css de tu pdf_qEvipJ.pdf
    $bgColor = $state['background'] ?? '#ffffff';
    $colsec = $state['colsecond'] ?? '#333333';
    $textColor = $state['ctexto'] ?? '#333333';
    
    $bgsecundario = $state['fondocolor'] ?? $bgColor;
    $textsubtitulo = $state['text'] ?? $colsec;
    $textdescrip = $state['secondary'] ?? $textColor;
    
    $botonfondo = $state['primary_button'] ?? $colsec;
    $botontexto = $state['button_text'] ?? '#ffffff';
    $headerfondo = $state['header'] ?? $colsec; // El catálogo añade soporte para fondo del header
    $footerfondo = $state['footer'] ?? $colsec;
@endphp


<div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-md text-xs select-none font-sans">
    
    
    <header class="p-4 transition-colors duration-200" style="background-color: {{ $bgColor }};">
        <div class="flex items-center gap-3">
            
            <div class="w-9 h-9 rounded bg-white flex items-center justify-center font-bold text-[10px] text-gray-700 shadow-sm shrink-0">
                Logo
            </div>
            
            <div class="overflow-hidden">
                <h1 class="font-bold text-sm truncate" style="color: {{ $textColor }};">
                    Nombre del Catálogo
                </h1>
                
            </div>
        </div>
    </header>

    
    <main class="p-4 transition-colors duration-200" style="background-color: {{ $bgColor }};">
        
        
        

        
        <div class="mb-3 border-l-4 pl-2" style="border-color: {{ $colsec }};">
            <h2 class="font-bold text-xs" style="color: {{ $textColor }};">
                Nuestros Productos
            </h2>
        </div>

        
        <div class="grid grid-cols-2 gap-2">
            <!-- Producto 1 -->
            <div class="rounded-lg border border-gray-100 dark:border-gray-800 p-2.5 shadow-sm transition-colors duration-200 flex flex-col justify-between" 
                 style="background-color: {{ $bgsecundario }};">
                <div>
                    
                    <div class="w-full h-16 bg-gray-200 dark:bg-gray-800 rounded mb-2 flex items-center justify-center text-gray-400 text-[9px]">
                        [ Producto ]
                    </div>
                    <!-- Textos del Producto -->
                    <h3 class="font-bold text-[11px] leading-tight mb-0.5 truncate" style="color: {{ $textsubtitulo }};">
                        Producto Destacado
                    </h3>
                    <p class="text-[9px] leading-snug line-clamp-2 mb-2" style="color: {{ $textdescrip }};">
                        Breve descripción de las características principales.
                    </p>
                </div>
                
                <div class="w-full py-1 rounded text-center text-[9px] font-bold shadow-sm cursor-pointer transition-colors duration-200"
                     style="background-color: {{ $botonfondo }}; color: {{ $botontexto }};">
                    Consultar
                </div>
            </div>

            
            <div class="rounded-lg border border-gray-100 dark:border-gray-800 p-2.5 shadow-sm transition-colors duration-200 flex flex-col justify-between" 
                 style="background-color: {{ $bgsecundario }};">
                <div>
                    <div class="w-full h-16 bg-gray-200 dark:bg-gray-800 rounded mb-2 flex items-center justify-center text-gray-400 text-[9px]">
                        [ Producto ]
                    </div>
                    <h3 class="font-bold text-[11px] leading-tight mb-0.5 truncate" style="color: {{ $textsubtitulo }};">
                        Segundo Item
                    </h3>
                    <p class="text-[9px] leading-snug line-clamp-2 mb-2" style="color: {{ $textdescrip }};">
                        Muestra visual utilizando las variables de color secundario.
                    </p>
                </div>
                <div class="w-full py-1 rounded text-center text-[9px] font-bold shadow-sm cursor-pointer transition-colors duration-200"
                     style="background-color: {{ $botonfondo }}; color: {{ $botontexto }};">
                    Consultar
                </div>
            </div>
        </div>
    </main>
    
    <footer class="p-3 text-center transition-colors duration-200 text-[9px]" style="background-color: {{ $footerfondo }}; color: {{ $textColor }};">
        <p class="font-semibold" style="color: {{ $textColor }};">© {{ date('Y') }} Catálogo </p>
        <p class="opacity-75">Todos los derechos reservados</p>
    </footer>
</div>
