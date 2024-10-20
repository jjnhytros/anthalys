<nav class="navbar navbar-expand-lg navbar-light bg-light mb-2">
    <a class="navbar-brand" href="{{ route('home') }}">{{ __('messages.navbar_brand') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('messages.toggle_navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            {{-- Aggiungi altre voci di navigazione qui --}}
        </ul>
        <!-- Switch per la modalità notturna -->
        <div class="form-check form-switch ms-auto">
            <input class="form-check-input" type="checkbox" id="darkModeSwitch"
                {{ optional(Auth::user()->character->profile)->night_mode ? 'checked' : '' }}>
            <label class="form-check-label" for="darkModeSwitch">Modalità Notturna</label>
        </div>
        @auth
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            {{ __('messages.logout') }}
                        </a>

                        {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form> --}}
                    </div>
                </li>
            </ul>
        @endauth
    </div>
</nav>
