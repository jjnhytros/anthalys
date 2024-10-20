@extends('layouts.main')

@section('content')
    <h1>Modifica Fonte</h1>

    <form action="{{ route('sources.update', $source->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Titolo</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $source->title }}" required>
        </div>

        <div class="form-group">
            <label for="description">Descrizione</label>
            <textarea name="description" id="description" class="form-control">{{ $source->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="author">Autore</label>
            <input type="text" name="author" id="author" class="form-control" value="{{ $source->author }}">
        </div>

        <div class="form-group">
            <label for="type">Tipo</label>
            <input type="text" name="type" id="type" class="form-control" value="{{ $source->type }}" required>
        </div>

        <div class="form-group">
            <label for="publication_date">Data di Pubblicazione</label>
            <input type="date" name="publication_date" id="publication_date" class="form-control"
                value="{{ $source->publication_date }}">
        </div>

        <div class="form-group">
            <label for="url">URL (opzionale)</label>
            <input type="url" name="url" id="url" class="form-control" value="{{ $source->url }}">
        </div>

        <button type="submit" class="btn btn-primary">Aggiorna Fonte</button>
    </form>
@endsection
