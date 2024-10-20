{{-- resources/views/anthaleja/sonet/rooms/create.blade.php --}}
@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <h2>Crea Nuova Stanza</h2>
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome della Stanza</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descrizione</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipo di Stanza</label>
                <select class="form-select" id="type" name="type">
                    <option value="public">Pubblica</option>
                    <option value="private">Privata</option>
                    <option value="invite-only">Solo su invito</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crea Stanza</button>
        </form>
    </div>
@endsection
