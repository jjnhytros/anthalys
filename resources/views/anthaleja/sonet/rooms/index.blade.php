{{-- resources/views/anthaleja/sonet/rooms/index.blade.php --}}
@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Elenco delle Stanze</h2>
            <a href="{{ route('rooms.create') }}" class="btn btn-primary">Crea Nuova Stanza</a>
        </div>

        @if ($rooms->isEmpty())
            <div class="alert alert-info">Non ci sono stanze disponibili al momento.</div>
        @else
            <div class="list-group">
                @foreach ($rooms as $room)
                    <a href="{{ route('rooms.show', $room->id) }}" class="list-group-item list-group-item-action">
                        <h5 class="mb-1">{{ $room->name }}</h5>
                        <p class="mb-1">{{ $room->description }}</p>
                        <small class="text-muted">{{ ucfirst($room->type) }} - Creato da:
                            {{ $room->creator->username }}</small>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
