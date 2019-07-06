<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>E &amp; R Travels</title>

        <!-- generics -->
        <link rel="icon" href="{{ asset('favicon/favicon-32.png') }}" sizes="32x32">
        <link rel="icon" href="{{ asset('favicon/favicon-57.png') }}" sizes="57x57">
        <link rel="icon" href="{{ asset('favicon/favicon-76.png') }}" sizes="76x76">
        <link rel="icon" href="{{ asset('favicon/favicon-128.png') }}" sizes="128x128">
        <link rel="icon" href="{{ asset('favicon/favicon-192.png') }}" sizes="192x192">
        <link rel="icon" href="{{ asset('favicon/favicon-228.png') }}" sizes="228x228">

        <!-- Android -->
        <link rel="shortcut icon" sizes="196x196" href="{{ asset('favicon/favicon-196.png') }}">

        <!-- iOS -->
        <link rel="apple-touch-icon" href="{{ asset('favicon/favicon-120.png') }}" sizes="120x120">
        <link rel="apple-touch-icon" href="{{ asset('favicon/favicon-152.png') }}" sizes="152x152">
        <link rel="apple-touch-icon" href="{{ asset('favicon/favicon-180.png') }}" sizes="180x180">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    </head>
    <body>
        <main id="app">
            <router-view></router-view>
        </main>
    </body>
</html>
