<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.head')

<body
    class="{{ optional(Auth::user()->character->profile)->night_mode === null || !optional(Auth::user()->character->profile)->night_mode ? 'light' : 'dark' }}">
    <!-- Imposta light se null o profilo non esiste -->
    <!-- Imposta light se null -->
    @include('layouts.partials.navbar_top')

    <main class="container-fluid">
        @include('layouts.partials.alerts')

        {{-- Content Yielded --}}
        @yield('content')
    </main>

    {{-- Scripts --}}
    {{-- @include('layouts.partials.scripts') <!-- Aggiungi i tuoi script qui --> --}}

    {{-- @include('layouts.partials.footer') --}}
    {{-- <script src="{{ asset('js/custom/custom.js') }}"></script> --}}
    <script>
        // Aggiunge la logica per attivare/disattivare la modalità notturna
        document.getElementById('darkModeSwitch').addEventListener('change', function() {
            let nightMode = this.checked;

            // Invia una richiesta AJAX per aggiornare la modalità notturna nel profilo
            fetch('/profile/night-mode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    night_mode: nightMode
                })
            }).then(() => {
                // Ricarica la pagina per applicare il tema
                location.reload();
            });
        });
    </script>
</body>

</html>
