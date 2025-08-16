@php
    $enlace = \App\Models\Enlace::find($this->data['enlace_id']);
@endphp

@if($enlace)
<div class="p-4 border rounded-lg">
    <p class="text-sm font-medium text-gray-700">Vista previa:</p>
    <img 
        src="{{ asset('storage/' . $enlace->logo_path) }}" 
        alt="{{ $enlace->nombre }}"
        class="h-16 w-16 object-contain mx-auto mt-2"
    >
    <p class="text-center mt-2 text-sm">{{ $enlace->nombre }}</p>
</div>
@endif