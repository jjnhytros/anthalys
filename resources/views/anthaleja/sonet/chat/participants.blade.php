@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>Chat Room: {{ $room->name }}</h1>

        <!-- Lista dei partecipanti -->
        <div class="chat-participants mb-4">
            <h4>Partecipanti</h4>
            <ul class="list-unstyled" id="participants-list">
                @foreach ($participants as $participant)
                    <li id="status-{{ $participant->id }}">
                        <span>
                            {{ $participant->username }}
                            @if ($participant->is_online)
                                {!! getIcon('circle-fill', 'bi', 'Online') !!}
                            @else
                                {!! getIcon('circle-fill', 'bi', 'Offline') !!}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Contenuto della chat -->
        <div class="chat-box">
            @yield('chat-content')
        </div>
    </div>

    <script>
        // Funzione per aggiornare dinamicamente lo stato online/offline dei partecipanti
        function updateStatus() {
            $.get("{{ route('sonet.users.status') }}", function(data) {
                data.forEach(function(user) {
                    const element = document.getElementById(`status-${user.id}`);
                    if (user.is_online) {
                        element.innerHTML = '{{ getIcon('circle-fill', 'bi', 'Online') }} ' + user.username;
                    } else {
                        element.innerHTML = '{{ getIcon('circle-fill', 'bi', 'Offline') }} ' + user
                        .username;
                    }
                });
            });
        }

        // Aggiorna lo stato ogni 10 secondi
        setInterval(updateStatus, 10000);
        updateStatus();
    </script>
@endsection
