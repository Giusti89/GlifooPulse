<x-layouts.principal titulo="Planes"
    url="{{ asset('./estilo/producto.css') }}?v={{ filemtime(public_path('./estilo/producto.css')) }}">
    <div class="principal">
        @foreach ($productos as $item)
            @if ($item->estado == true)
                <div class="tarjeta" style="background-image: url(/storage/{{ $item->image_url }})">
                    <!-- Efectos de partículas -->
                    <div class="tarjeta__particulas" id="particulas-{{ $item->id }}">
                        <!-- Las partículas se generan con JavaScript -->
                    </div>

                    <!-- Efecto de brillo -->
                    <div class="tarjeta__brillo"></div>

                    <!-- Etiqueta -->
                    <div class="tarjeta__header">
                        <h2 class="tarjeta__titulo">{{ $item->nombre }}</h2>
                    </div>

                    <div class="tarjeta__body">
                        <div class="tarjeta__descripcion">
                            {!! str($item->descripcion)->sanitizeHtml() !!}
                        </div>
                    </div>

                    <div class="tarjeta__precio">
                        <p class="tarjeta__descripcion">{{ $item->precio }}</p>
                    </div>

                    <div class="tarjeta__footer">
                        @php
                            $encryptedId = Crypt::encrypt($item->id);
                        @endphp

                        <x-layouts.btnenviodat class="modificar" rutaEnvio="registro" dato="{{ $encryptedId }}"
                            nombre="REGISTRATE">
                        </x-layouts.btnenviodat>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <script src="{{ asset('./dinamico/paquetes.js') }}"></script>
</x-layouts.principal>
