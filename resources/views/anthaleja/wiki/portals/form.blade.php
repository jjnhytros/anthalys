@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>{{ isset($portal) ? 'Modifica Portale' : 'Crea Nuovo Portale' }}</h1>

        <form method="POST" action="{{ isset($portal) ? route('portals.update', $portal->id) : route('portals.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($portal))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Nome del Portale</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $portal->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Descrizione</label>
                <textarea name="description" id="description" class="form-control" required>{{ old('description', $portal->description ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="cover_image">Immagine di Copertina</label>
                <input type="file" name="cover_image" id="cover_image" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($portal) ? 'Aggiorna' : 'Crea' }}</button>
        </form>
    </div>
@endsection
