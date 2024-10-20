@extends('main.app')

@section('content')
    <h1>Lavori di {{ $character->name }}</h1>

    <a href="{{ route('characters.occupations.create', $character) }}" class="btn btn-primary">Aggiungi Lavoro</a>

    <ul>
        @foreach ($occupations as $occupation)
            <li>
                <strong>{{ $occupation->title }}</strong> - {{ $occupation->salary }} $/giorno -
                {{ $occupation->hours_per_day }} ore/giorno
            </li>
        @endforeach
    </ul>
@endsection
