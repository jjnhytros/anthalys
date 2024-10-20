@extends('main.app')

@section('content')
    <h1>Aggiungi Lavoro per {{ $character->name }}</h1>

    <form action="{{ route('characters.occupations.store', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Titolo del Lavoro</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="salary">Salario giornaliero</label>
            <input type="number" name="salary" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="hours_per_day">Ore al giorno</label>
            <input type="number" name="hours_per_day" class="form-control" min="1" max="28" required>
        </div>
        <button type="submit" class="btn btn-primary">Salva</button>
    </form>
@endsection
