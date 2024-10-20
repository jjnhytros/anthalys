@extends('layouts.main')

@section('content')
    <h1>Eventi Sociali di {{ $character->name }}</h1>

    @if ($socialEvents->count())
        <ul>
            @foreach ($socialEvents as $event)
                <li>
                    <strong>{{ $event->event_type }}</strong>: {{ $event->description }}
                    - Felicità: {{ $event->effect_on_happiness }}
                    - Forza della Relazione: {{ $event->effect_on_relationship_strength }}
                </li>
            @endforeach
        </ul>
    @else
        <p>Non ci sono eventi sociali registrati.</p>
    @endif
@endsection
