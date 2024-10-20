<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timezones Management</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- Assuming you're using Laravel Mix -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Timezones Management</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('timezones.index') }}">Timezones</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="content">
        @yield('content') <!-- This will render the page-specific content -->
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
