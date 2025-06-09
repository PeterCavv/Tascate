<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Proposal Approved</title>
</head>
<body>
<h1>Task Proposal Approved</h1>
<p>Felicidades, tu solicitud ha sido aceptada!</p>

<ul>
    <li><strong>Nombre de la Tasca:</strong> {{ $tascaProposal->tasca_name }}</li>
    <li><strong>Dirección:</strong> {{ $tascaProposal->address }}</li>
    <li><strong>Teléfono:</strong> {{ $tascaProposal->telephone }}</li>
    <li><strong>CIF:</strong> {{ $tascaProposal->cif }}</li>
    <li><strong>DNI:</strong> {{ $tascaProposal->dni }}</li>
</ul>
<h2>{{$tascaProposal->status}}</h2>

<p>Gracias por tu propuesta. Ahora puedes acceder al sistema con tus credenciales.</p>
</body>
</html>
