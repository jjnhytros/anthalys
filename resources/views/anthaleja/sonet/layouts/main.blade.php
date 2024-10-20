{{-- resources/views/layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body>
    @include('layouts.partials.navbar_top')

    @include('layouts.partials.alerts')
    <div class="container-fluid mt-2">
        <div class="row justify-content-center">
            @include('anthaleja.sonet.layouts.partials.sonet_sidebar_left')

            <!-- Colonna centrale: Timeline dei sonet -->
            {{-- <main class="col-md-8 ms-sm-auto col-lg-8 px-md-4"> --}}
            <main class="col-md-6">
                @yield('content')
            </main>
            @include('anthaleja.sonet.layouts.partials.sonet_sidebar_right')
        </div>
    </div>

    @auth
        {{-- @include('layouts.partials.
        ') --}}
    @endauth

    {{-- @include('layouts.partials.footer') --}}
</body>

</html>
