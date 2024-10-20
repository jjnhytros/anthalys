@extends('layouts.main')

@section('content')
    <h1>Pianificazione Automatica per {{ $character->name }}</h1>

    <h3>Pianifica automaticamente le azioni del personaggio in base al meteo previsto</h3>

    <form action="{{ route('characters.auto-plan', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="days">Numero di giorni da pianificare</label>
            <input type="number" name="days" class="form-control" value="7" min="1" max="14">
        </div>
        <button type="submit" class="btn btn-primary">Pianifica Azioni</button>
    </form>

    @if (isset($plannedActions))
        <h3>Azioni Pianificate per i Prossimi Giorni</h3>
        <ul>
            @foreach ($plannedActions as $action)
                <li>
                    Giorno {{ $action['day'] }}/{{ $action['month'] }} - Azione: {{ $action['action'] }}
                    (Condizioni: {{ $character->energy < 20 ? 'Bassa Energia' : '' }}
                    {{ $character->happiness < 20 ? 'Bassa Felicità' : '' }} {{ $forecast->weather_type }})
                </li>
            @endforeach
        </ul>
    @endif
@endsection
