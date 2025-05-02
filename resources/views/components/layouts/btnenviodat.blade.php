<link rel="stylesheet" href="{{asset('./estilo/btnenviodat.css')}}">

<a href="{{ route($rutaEnvio, $dato) }}" target="{{$estado ?? ''}}" >
    <button type="button" class="modificar" {{$estado ?? ''}} style="background-color:{{$color ?? ''}} ">
        {{$nombre}}
    </button>
</a>