<!DOCTYPE html>
<html>

<head>
    <title>Nueva Suscripción</title>
</head>

<body>
    <h1 style="color: #fcd031">Solicitud de registro al servicio Glifoo pulse</h1>
    <p><strong>Usuario:</strong> {{ $user->name }} {{ $user->lastname }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Numero de celular:</strong> {{ $user->phone }}</p>
    <p><strong>Escogio el paquete:</strong> {{ $paquete->nombre }}</p>
    <p><strong>Por un tiempo de:</strong> {{ $meses }} <strong> meses</strong> </p>
    <p><strong>Con un costo de:</strong> {{ number_format($paquete->precio * $meses, 2) }} <strong>Bs.</strong> </p>
    <p><strong>Fecha de registro: </strong> {{ $user->created_at }}</p>
    <p>---------------------------------------------------------</p>
       <p> Glifoo - Comunicación Digital </p>
</body>

</html>
