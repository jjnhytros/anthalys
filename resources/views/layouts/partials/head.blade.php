<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="J.J.Nhytros">

    <meta name="msapplication-TileColor" content="#B22222">
    <meta name="theme-color" content="#00335B" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('meta')

    {{-- Preload fonts (uncomment if needed) --}}
    {{-- <link rel="preload" href="{{ asset('webfonts/EasyReading.woff') }}" as="font" type="font/woff" crossorigin> --}}

    {{-- CSS files --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ath_bs5.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/prism.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('css')

    {{-- JS files --}}
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('js/prism.js') }}"></script> --}}

    {{-- Custom Scripts --}}
    {{-- <script src="{{ asset('js/custom.js') }}"></script> --}}
    @yield('js')

    <title>{{ config('app.name') }}{{ isset($title) ? ' | ' . $title : '' }}</title>
</head>
