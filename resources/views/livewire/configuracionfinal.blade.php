<div>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-2">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Gestión de la web
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Controla el estado y comparte tu enlace
                </p>
            </div>

            <!-- Card Principal -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <!-- Título y Estado -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $spot->titulo }}
                    </h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ $spot->estado ? '🟢 Activo' : '🔴 Inactivo' }}
                        </span>
                    </div>
                </div>

                <!-- Toggle Switch -->
                <div
                    class="flex items-center justify-between py-4 border-t border-b border-gray-100 dark:border-gray-700 mb-6">
                    <div>
                        <h3 class="font-medium text-gray-900 dark:text-white">Estado de tu Web</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $spot->estado ? 'Tu página es visible al público' : 'Tu página está oculta' }}
                        </p>
                    </div>

                    <button wire:click="toggleEstado" wire:loading.attr="disabled"
                        class="px-4 py-2 font-medium text-white rounded-lg transition-colors
            {{ $spot->estado
                ? 'bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700'
                : 'bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700' }}">
                        {{ $spot->estado ? '🔴 Click aqui para desactivar' : '🟢 Click aqui para activar' }}
                        <span wire:loading
                            class="inline-block w-4 h-4 ml-2 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    </button>
                </div>

                <!-- URL para Compartir -->
                <div>
                    <h3 class="font-medium text-gray-900 dark:text-white mb-3">Compartir tu web</h3>
                    <div class="flex items-center space-x-3">
                        <div class="flex-1">
                            <input type="text" id="enlace-landing-{{ $spot->id }}" value="{{ url($spot->slug) }}"
                                readonly
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white text-sm" />
                        </div>
                        <button wire:click="copiarEnlace" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                            <span wire:loading.remove>📋</span>
                            <span wire:loading>⏳</span>
                            <span>Copiar</span>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Comparte este enlace con tus clientes o en tus redes sociales
                    </p>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $spot->contador }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Visitas totales</div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $spot->socials->count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Enlaces sociales</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('copiar-enlace', (event) => {
        // Crear un elemento temporal para copiar
        const tempInput = document.createElement('input');
        tempInput.value = event.enlace;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        // Notificación
        Livewire.dispatch('notify', {
            type: 'success',
            message: 'Enlace copiado al portapapeles!'
        });
    });
});
</script>