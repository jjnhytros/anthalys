{{-- resources/views/anthaleja/sonet/rooms/show.blade.php --}}
@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <!-- Dettagli della stanza -->
        <div class="mb-4">
            <h2>{{ $room->name }}</h2>
            <p class="text-muted">{{ ucfirst($room->type) }} - Creato da: {{ $room->creator->username }}</p>
            <p>{{ $room->description }}</p>
        </div>

        <!-- Form per l'invio dei messaggi -->
        <div class="mb-4">
            <h4>Invia un nuovo messaggio</h4>
            <form action="{{ route('rooms.messages.store', $room) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="message" class="form-control" rows="3" placeholder="Scrivi un messaggio..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Invia</button>
            </form>
        </div>

        <!-- Elenco dei messaggi -->
        <div class="mb-4">
            <h4>Messaggi nella Stanza</h4>
            @if ($room->messages->isEmpty())
                <div class="alert alert-info">Non ci sono messaggi nella stanza.</div>
            @else
                <ul class="list-group">
                    @foreach ($room->messages as $message)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $message->sender->username }}</strong>:
                                <p>{{ $message->message }}</p>
                                <small class="text-muted">{{ $message->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            @if ($message->character_id == Auth::user()->character->id)
                                <div class="btn-group">
                                    <a href="{{ route('rooms.messages.edit', [$room, $message]) }}"
                                        class="btn btn-sm btn-warning">Modifica</a>
                                    <form action="{{ route('rooms.messages.destroy', [$room, $message]) }}" method="POST"
                                        onsubmit="return confirm('Sei sicuro di voler eliminare questo messaggio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Elimina</button>
                                    </form>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Membri della stanza -->
        <div class="mb-4">
            <h4>Membri della Stanza</h4>
            @if ($room->members->isEmpty())
                <div class="alert alert-info">Non ci sono membri nella stanza.</div>
            @else
                <ul class="list-group">
                    @foreach ($room->members as $member)
                        <li class="list-group-item">
                            {{ $member->character->username }} - <span
                                class="badge bg-info">{{ ucfirst($member->role) }}</span>
                            @if ($member->role !== 'admin' && Auth::user()->character->id === $room->created_by)
                                <form action="{{ route('rooms.members.updateRole', [$room]) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="character_id" value="{{ $member->character->id }}">
                                    <select name="role" class="form-select d-inline-block w-auto">
                                        <option value="member" {{ $member->role == 'member' ? 'selected' : '' }}>Member
                                        </option>
                                        <option value="moderator" {{ $member->role == 'moderator' ? 'selected' : '' }}>
                                            Moderator</option>
                                        <option value="admin" {{ $member->role == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Aggiorna</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Torna all'elenco delle stanze</a>
    </div>
@endsection
