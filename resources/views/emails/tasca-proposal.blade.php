<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Propuesta de Tasca</title>
</head>
<body>
<h1>Nueva Propuesta de Tasca Pendiente</h1>
<p>Se ha creado una nueva propuesta de tasca que está pendiente de aceptar o rechazar. A continuación, se muestra la información de la tasca:</p>

<ul>
    <li><strong>Nombre de la Tasca:</strong> {{ $tascaProposal->tasca_name }}</li>
    <li><strong>Dirección:</strong> {{ $tascaProposal->address }}</li>
    <li><strong>Teléfono:</strong> {{ $tascaProposal->telephone }}</li>
    <li><strong>CIF:</strong> {{ $tascaProposal->cif }}</li>
    <li><strong>DNI:</strong> {{ $tascaProposal->dni }}</li>
</ul>

<p>Por favor, revisa esta propuesta en el sistema de administración.</p>
</body>
</html>
