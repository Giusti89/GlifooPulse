<!-- mensajes de confirmacion -->
@if (session('msj') == 'susterminada')
    <script>
        Swal.fire({
            title: "Suscripcion finalizada",
            text: "Su suscripción a terminado ingresara a un formulario de resuscripcion.",
            icon: "warning"
        });
    </script>
@endif

@if (session('msj') == 'sinsuscripcion')
    <script>
        Swal.fire({
            title: "No se pudo realizar la solicitud",
            text: "Tu suscripcion esta en verificación.",
            icon: "warning"
        });
    </script>
@endif

@if (session('msj') == 'suscripcion')
    <script>
        Swal.fire({
            title: "Su registro fue exitoso",
            text: "Recibira un mensaje a su numero de celular.",
            icon: "success"
        });
    </script>
@endif

@if (session('msj') == 'verifsus')
    <script>
        Swal.fire({
            title: "Registro en verificacion",
            text: "Tu suscripcion esta en verificacion recibiras un mensaje a tu numero de celular.",
            icon: "warning"
        });
    </script>
@endif

@if (session('msj') == 'pagvencida')
    <script>
        Swal.fire({
            title: "Restringido",
            text: "La pagina web no esta disponible.",
            icon: "warning"
        });
    </script>
@endif

@if (session('msj') == 'solievi')
    <script>
        Swal.fire({
            title: "Solicitud entregada",
            text: "Su solicitud fue entregada de manera correcta.",
            icon: "success"
        });
    </script>
@endif

<!-- mensajes de confirmacion -->
