<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            background: #4CAF50;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .email-body {
            padding: 20px;
        }
        .email-footer {
            background: #f1f1f1;
            text-align: center;
            padding: 10px;
            font-size: 0.9em;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>¡Nueva Reserva en tu Tasca!</h1>
    </div>
    <div class="email-body">
        <p>Hola <strong>{{ $tasca->name }}</strong>,</p>
        <p>Te informamos que el usuario <strong>{{ $user->name }}</strong> ha realizado una reserva en tu tasca.</p>
        <p><strong>Detalles de la Reserva:</strong></p>
        <ul>
            <li><strong>Fecha:</strong> {{ $reservation->reservation_date }}</li>
            <li><strong>Hora:</strong> {{ $reservation->reservation_time }}</li>
            <li><strong>Número de Personas:</strong> {{ $reservation->people }}</li>
        </ul>
        <p>Por favor, asegúrate de preparar todo para recibir a tus clientes.</p>
        <a href="{{ url('/reservations/' . $reservation->id) }}" class="button">Ver Detalles de la Reserva</a>
    </div>
    <div class="email-footer">
        <p>Gracias por usar {{ config('app.name') }}.</p>
    </div>
</div>
</body>
</html>
