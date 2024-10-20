@extends('layouts.main')

@section('content')
    <h1>Stato del Lavoro</h1>

    <h2>Professione: {{ $character->profession->name }}</h2>
    <p>Stipendio: {{ $character->profession->salary }}</p>
    <p>Livello di lavoro: {{ $character->work_level }}</p>
    <p>Esperienza di lavoro: {{ $character->work_experience }}</p>

    <h3>Competenze</h3>
    <ul>
        @foreach ($character->skills as $skill)
            <li>{{ $skill->name }}: Livello {{ $skill->level }}</li>
        @endforeach
    </ul>

    <form action="{{ route('work.start') }}" method="POST">
        @csrf
        <label for="hours">Ore di lavoro:</label>
        <input type="number" name="hours" value="8">
        <button type="submit" class="btn btn-primary">Inizia Lavoro</button>
    </form>
@endsection
