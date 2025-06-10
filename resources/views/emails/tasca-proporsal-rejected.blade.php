<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Proposal Rejected</title>
</head>
<body>
<h1>Task Proposal Rejected</h1>
<p>Lamentamos informarte que tu solicitud ha sido rechazada.</p>

<ul>
    <li><strong>Nombre de la Tasca:</strong> {{ $tascaProposal->tasca_name }}</li>
    <li><strong>Dirección:</strong> {{ $tascaProposal->address }}</li>
    <li><strong>Teléfono:</strong> {{ $tascaProposal->telephone }}</li>
    <li><strong>CIF:</strong> {{ $tascaProposal->cif }}</li>
    <li><strong>DNI:</strong> {{ $tascaProposal->dni }}</li>
</ul>

<p>Si tienes alguna duda, no dudes en contactarnos.</p>
</body>
</html>
