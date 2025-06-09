<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Welcome to Our Platform') }}</title>

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
</head>
<body class="font-sans antialiased">
<div class="email-template">
    <h1>{{ __('Welcome to Our Platform') }}</h1>
    <p>{{ __('Hello, :name!', ['name' => $user->name]) }}</p>
    <p>{{ __('We are thrilled to have you on board. Thank you for joining our platform.') }}</p>
    <p>{{ __('To get started, click the button below to log in to your account:') }}</p>
    <a href="{{ url('/login') }}" class="button">{{ __('Log In') }}</a>
    <p>{{ __('If you have any questions, feel free to reach out to our support team. We\'re here to help!') }}</p>
    <p>{{ __('Enjoy your journey with us!') }}</p>
    <p>{{ __('Best regards,') }}<br>{{ __('The Team') }}</p>
</div>
</body>
</html>
