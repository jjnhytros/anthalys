@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Aggiungi un Progetto al tuo Portafoglio</h2>
        <form action="{{ route('portfolios.store') }}" method="POST" class="mt-3">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Titolo del Progetto:</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrizione:</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="link" class="form-label">Link al Progetto (opzionale):</label>
                <input type="url" name="link" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Aggiungi Progetto</button>
        </form>
    </div>
@endsection
