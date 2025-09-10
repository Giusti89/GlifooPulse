<!DOCTYPE html>
<html>
<head>
    
    <title>Su solicitud de renovacion fue procesada</title>
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
    <h1>Glifoo pulse </h1>
    
    <p><strong> Estimado Usuario:</strong> {{ $user->name }} {{ $user->lastname }}</p>
    <p><strong>La renovación de su suscripcion fue efectuada de manera correcta</strong> </p>   
    
    <div class="footer">
        <p>Glifoo - Comunicación Digital</p>
        
    </div>
</body>
</html>