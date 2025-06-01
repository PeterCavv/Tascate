<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="/images/tascate-32x32px.png" type="image/x-icon">
        <link rel="icon" href="/images/tascate-32x32px.png" type="image/x-icon">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/tascate-32x32px.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/images/tascate-192x192px.png">
        <link rel="icon" type="image/png" sizes="512x512" href="/images/tascate-512x512px.png">
        <link rel="apple-touch-icon" sizes="192x192" href="/images/tascate-192x192px.png">
        <meta name="msapplication-TileImage" content="/images/tascate-192x192px.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
