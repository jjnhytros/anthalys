<div>
    <div class="dropup ms-auto fixed-bottom">
        <button class="btn phone-icon" id="phoneDropup" data-bs-toggle="dropdown" aria-expanded="false">
            <i id="phoneIcon" class="bi bi-phone"></i> <!-- Icona del telefono -->
        </button>
        <ul class="dropdown-menu dropdown-menu-end phone-screen" aria-labelledby="phoneDropup">
            <!-- Header con l'ora e la batteria -->
            <div class="d-flex justify-content-between align-items-center phone-header">
                <i id="notificationIcon" class="bi bi-bell me-1"></i>
                <span id="phone-time" class="phone-time">{{ now()->format('H:i') }}</span>
                <div class="d-flex align-items-center">
                    <i class="bi bi-battery-half"></i> <!-- Icona della batteria -->
                    <!-- Dropdown dell'icona gear -->
                    <div class="dropdown" onclick="event.stopPropagation();">
                        <a href="#" class="btn p-0" id="settingsDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-gear"></i> <!-- Icona gear a destra della batteria -->
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                            <li><a class="dropdown-item" href="#">{{ __('messages.settings_option1') }}</a></li>
                            <li><a class="dropdown-item" href="#">{{ __('messages.settings_option2') }}</a></li>
                            <li><a class="dropdown-item" href="#">{{ __('messages.settings_option3') }}</a></li>
                        </ul>
                    </div>
                </div>
                {{ $athNow }}
            </div>
            <!-- App sullo schermo (icone) -->
            <div class="row text-center phone-apps mt-2">
                @foreach ($applications as $application)
                    <div class="col-3">
                        <a href="{{ $application->link }}" class="btn phone-app">
                            <i class="{{ $application->icon }}"></i>
                            @if ($application->name == 'SoNet')
                                <span id="unread-count" class="badge bg-danger"></span>
                            @endif
                            <span class="phone-app-label">{{ $application->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Dockbar in basso con icone per chiamata e contatti -->
            <div class="d-flex justify-content-evenly phone-dockbar mt-3">
                <a href="#" class="btn btn-success rounded-circle p-2 small-btn">
                    <i class="bi bi-telephone-fill"></i>
                </a>
                <a href="#" class="btn btn-primary rounded-circle p-2 small-btn">
                    <i class="bi bi-person-lines-fill"></i>
                </a>
            </div>
        </ul>
    </div>

    <script></script>
</div>
