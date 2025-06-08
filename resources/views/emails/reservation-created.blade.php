<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

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

        <!-- Email Template -->
        <div class="email-template">
            <h1>{{ __('Reservation Created') }}</h1>
            <p>{{ __('Hello') }}, {{$customer->user->name}}</p>
            <p>{{ __('Thank you for your reservation!') }}</p>
            <p>{{ __('Your reservation details are as follows:') }}</p>

            <h2>Tasca:</h2>
            <ul>
                <li><strong>{{ __('Name') }}:</strong> {{ $tasca->name  }}</li>
                <li><strong>{{ __('Address') }}:</strong> {{$tasca->address}}</li>
                <li><strong>{{ __('Phone') }}:</strong> {{ $tasca->telephone }}</li>
            </ul>
            <h2>{{ __('Reservation Details') }}</h2>
            <ul>
                <li><strong>{{ __('Price:') }}:</strong> {{ $reservation->price }}</li>
                <li><strong>{{ __('Reservation  date:') }}:</strong> {{ $reservation->reservation_date }}</li>
                <li><strong>{{ __('Reservation Time') }}:</strong> {{ $reservation->reservation_time }}</li>
                <li><strong>{{ __('People') }}:</strong> {{ $reservation->people }}</li>
            </ul>

            <h3>{{__('Observations')}}:</h3>
            <p>{{ $reservation->observations }}</p>

            <h4>{{__('Thanks')}}</h4>
        </div>
