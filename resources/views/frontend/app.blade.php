<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{--    <title>{{ config('app.name', 'Laravel') }}</title>--}}

    {!! SEO::generate() !!}

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

    <!-- Stylesheets -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='{{ asset('vendor/unicons-2.0.1/css/unicons.css') }}' rel='stylesheet'>



{{--    <link href="{{ asset('css/style.css?v='.time()) }}" rel="stylesheet">--}}
    <link href="{{ asset('css/style.css?v=12') }}" rel="stylesheet">

    {{--    <link href="{{ asset('css/responsive.css?v='.time()) }}" rel="stylesheet">--}}
    <link href="{{ asset('css/responsive.css?v=5') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">

    <!-- Vendor Stylesheets -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/OwlCarousel/assets/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/semantic/semantic.min.css') }}">



    @yield('styles')
</head>
<body>
<a id="buttonToTop">
    <i class="uil uil-arrow-circle-up"></i>
</a>
@include('frontend.shared.country_modal')
@include('frontend.shared.categories_modal')
@include('frontend.shared.search_modal')
@include('frontend.shared.cart_modal')
@include('frontend.shared.header')
@yield('content')
@include('frontend.shared.footer')

<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/OwlCarousel/owl.carousel.js') }}"></script>
<script src="{{ asset('vendor/semantic/semantic.min.js') }}"></script>
<script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('js/custom.js?v=1') }}"></script>
<script src="{{ asset('js/offset_overlay.js') }}"></script>
<script src="{{ asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ asset('js/global.js') }}"></script>



<script>
    $(document).ready(function(){
        $("#country_modal_btn").trigger('click');
        @if(session()->has('open_cart'))
            $("#cart_btn").trigger('click');
        @endif
        @if(session()->has('open_cart_no_animation'))
        $("#cart_btn").trigger('click');
        @endif



        $(window).scroll(function() {
            if ($(window).scrollTop() > 300) {
                $('#buttonToTop').addClass('show');
            } else {
                $('#buttonToTop').removeClass('show');
            }
        });
        $('#buttonToTop').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop:0}, '300');
        });

    });
</script>

@yield('scripts')

</body>
</html>
