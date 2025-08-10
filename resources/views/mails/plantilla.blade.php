<!DOCTYPE html>
<html>
<head>
    <title>Solicitud de Compra de Plantilla</title>
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
    <h1>Solicitud de compra de plantilla</h1>
    
    <p><strong>Usuario:</strong> {{ $user->name }} {{ $user->lastname }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Teléfono:</strong> {{ $user->phone }}</p>
    
    <h3>Detalles de la compra de la plantilla:</h3>
    <p><strong>Paquete seleccionado:</strong> {{ $landing->nombre }}</p>
    <p><strong>Precio total:</strong> {{ number_format($landing->precio, 2) }} Bs.</p>
    
    <div class="footer">
        <p>Glifoo - Comunicación Digital</p>
        <p>Fecha de solicitud: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>