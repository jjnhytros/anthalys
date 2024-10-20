@extends('main.app')

@section('content')
    <h1>Abilità di {{ $character->name }}</h1>

    <a href="{{ route('characters.skills.create', $character) }}" class="btn btn-primary">Aggiungi Abilità</a>

    <ul>
        @foreach ($skills as $skill)
            <li>
                <strong>{{ $skill->name }}</strong> - Livello: {{ $skill->level }}
                <form action="{{ route('characters.skills.update', $skill) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="number" name="level" value="{{ $skill->level }}" min="1" max="10">
                    <button type="submit" class="btn btn-success">Aggiorna</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
