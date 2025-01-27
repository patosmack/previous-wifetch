<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json?v=2') }}">
    <meta name="msapplication-TileColor" content="#3498db">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/nms-icon-144x144.png') }}">
    <meta name="theme-color" content="#FFF400">

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css?v=4') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/awesome/fontawesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/awesome/fa-light.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/awesome/fa-regular.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/awesome/fa-solid.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/awesome/fa-brands.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.min.css?v=' . time()) }}">

    @yield('styles')
</head>
<body>
@include('frontend.shared.header')
@yield('content')
@include('frontend.shared.footer')

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.chained.remote.js') }}"></script>

@yield('scripts')

</body>
</html>
