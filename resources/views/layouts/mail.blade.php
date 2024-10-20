<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body>
    @include('layouts.partials.navbar_top')

    <main class="container-fluid">
        @include('layouts.partials.alerts')
        @yield('content')
    </main>
    {{-- @include('layouts.partials.navbar_bottom') --}}

    @include('layouts.partials.footer')

</body>

</html>
