@extends('layouts.main')

@section('content')
    <h1>Suggerisci una nuova Fonte</h1>

    <form action="{{ route('suggested_sources.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Titolo</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Descrizione</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="author">Autore</label>
            <input type="text" name="author" id="author" class="form-control">
        </div>

        <div class="form-group">
            <label for="type">Tipo</label>
            <input type="text" name="type" id="type" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="url">URL (opzionale)</label>
            <input type="url" name="url" id="url" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Suggerisci Fonte</button>
    </form>
@endsection
