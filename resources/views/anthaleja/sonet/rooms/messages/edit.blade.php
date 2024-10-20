{{-- resources/views/anthaleja/sonet/rooms/messages/edit.blade.php --}}

@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <h2>Modifica Messaggio</h2>

        <form action="{{ route('rooms.messages.update', [$room, $message]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <textarea name="message" class="form-control" rows="3" required>{{ $message->message }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Aggiorna</button>
            <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary">Annulla</a>
        </form>
    </div>
@endsection
