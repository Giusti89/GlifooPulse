<div>
    <div class="space-y-6 mb-4">
        {{-- Mensaje de bienvenida con soporte dark mode --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-200">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">¡Hola, {{ auth()->user()->name }}! 👋
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Estamos emocionados de tenerte aquí. Para comenzar a usar el catálogo, necesitas crear algunos
                        elementos básicos.
                        Sigue estos pasos y en menos de 5 minutos tendrás todo configurado.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tips cards con soporte dark mode --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            {{-- Card 1: Organiza mejor --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors duration-200 hover:shadow-md dark:hover:shadow-gray-900/30">
                <div
                    class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900 dark:text-white">Organiza mejor</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Las categorías ayudan a tus clientes a
                    encontrar productos más fácilmente.</p>
            </div>

            {{-- Card 2: Precios competitivos --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors duration-200 hover:shadow-md dark:hover:shadow-gray-900/30">
                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900 dark:text-white">Precios competitivos</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Investigá precios similares en el mercado para
                    ser más competitivo.</p>
            </div>

            {{-- Card 3: Ayuda --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors duration-200 hover:shadow-md dark:hover:shadow-gray-900/30">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-medium text-gray-900 dark:text-white">¿Necesitas ayuda?</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Nuestro equipo está disponible para asistirte
                    en cada paso.</p>
            </div>
        </div>
        <form wire:submit.prevent="create">
            {{ $this->form }}
        </form>
        <x-filament-actions::modals />
    </div>
