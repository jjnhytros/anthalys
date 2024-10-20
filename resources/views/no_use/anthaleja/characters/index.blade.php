@extends('layouts.main')

@section('content')
    <h1>I tuoi personaggi</h1>

    <a href="{{ route('characters.create') }}" class="btn btn-primary">Crea Nuovo Personaggio</a>

    <ul>
        @foreach ($characters as $character)
            <li>
                <a href="{{ route('characters.show', $character) }}">{{ $character->name }}</a>
            </li>
        @endforeach
    </ul>
@endsection
