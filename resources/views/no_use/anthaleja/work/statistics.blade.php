@extends('layouts.main')

@section('content')
    <h1>Statistiche di Lavoro</h1>

    <ul>
        <li>Ore lavorate totali: {{ $character->total_hours_worked }}</li>
        <li>Missioni completate: {{ $character->missionsCompleted()->count() }}</li>
        <li>Fedeltà alla professione: {{ $character->loyalty }}</li>
    </ul>
@endsection
