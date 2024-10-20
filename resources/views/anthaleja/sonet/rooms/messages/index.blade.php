@extends('layouts.main')

@section('content')
    <div class="container">
        <h2>Messaggi nella Stanza: {{ $room->name }}</h2>
        <p>{{ $room->description }}</p>

        <!-- Elenco dei messaggi -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Messaggi</h5>
            </div>
            <div class="card-body">
                @forelse ($messages as $message)
                    <div class="mb-3">
                        <strong>{{ $message->sender->username }}</strong>:
                        <p>{{ $message->message }}</p>
                        <small class="text-muted">{{ $message->created_at->format('d M Y, H:i') }}</small>

                        <!-- Azioni consentite in base ai permessi -->
                        @if ($message->character_id === Auth::user()->character->id || $room->isModerator(Auth::user()->character->id))
                            <form action="{{ route('rooms.messages.destroy', [$room, $message]) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                            </form>
                        @endif
                    </div>
                    <hr>
                @empty
                    <p>Nessun messaggio presente nella stanza.</p>
                @endforelse
            </div>
        </div>

        <!-- Form per aggiungere un nuovo messaggio -->
        <div class="mt-4">
            <form action="{{ route('rooms.messages.store', $room) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">Nuovo Messaggio</label>
                    <textarea id="message" name="message" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
            </form>
        </div>
    </div>
@endsection
