<!DOCTYPE html>
<html>
<head>
    <title>Solicitud de Renovación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        h1 {
            color: #fcd031;
        }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Solicitud de renovación de servicio</h1>
    
    <p><strong>Usuario:</strong> {{ $user->name }} {{ $user->lastname }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Teléfono:</strong> {{ $user->phone }}</p>
    
    <h3>Detalles de la renovación:</h3>
    <p><strong>Paquete seleccionado:</strong> {{ $paquete->nombre }}</p>
    <p><strong>Duración:</strong> {{ $meses }} mes(es)</p>
    <p><strong>Precio total:</strong> {{ number_format($paquete->precio * $meses, 2) }} Bs.</p>
    
    <div class="footer">
        <p>Glifoo - Comunicación Digital</p>
        <p>Fecha de solicitud: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>