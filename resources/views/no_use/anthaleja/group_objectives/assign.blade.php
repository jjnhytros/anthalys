@extends('layouts.main')

@section('content')
    <h1>Assegna un Obiettivo di Gruppo</h1>

    <!-- Form per assegnare un nuovo obiettivo di gruppo -->
    <form action="{{ route('group-objectives.assign') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="objective_name">Nome dell'Obiettivo</label>
            <input type="text" name="objective_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Descrizione dell'Obiettivo</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="characters">Seleziona i Personaggi Partecipanti</label>
            <select name="characters[]" class="form-control" multiple required>
                @foreach ($characters as $character)
                    <option value="{{ $character->id }}">{{ $character->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="reward_happiness">Ricompensa in Felicità</label>
            <input type="number" name="reward_happiness" class="form-control" min="0" required>
        </div>

        <div class="form-group">
            <label for="reward_money">Ricompensa Monetaria</label>
            <input type="number" name="reward_money" class="form-control" min="0" required>
        </div>

        <button type="submit" class="btn btn-primary">Assegna Obiettivo</button>
    </form>

    <!-- Elenco degli obiettivi attivi -->
    <h3>Obiettivi di Gruppo Attivi</h3>

    @if ($activeObjectives->count())
        <ul>
            @foreach ($activeObjectives as $objective)
                <li>
                    <strong>{{ $objective->objective_name }}</strong>: {{ $objective->description }}
                    <br>
                    Partecipanti:
                    <ul>
                        @foreach ($objective->characters as $participant)
                            <li>{{ $participant->name }}</li>
                        @endforeach
                    </ul>
                    Ricompensa Felicità: +{{ $objective->reward_happiness }} | Ricompensa Denaro:
                    +{{ $objective->reward_money }} $
                    <form action="{{ route('group-objectives.complete', $objective) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Completa Obiettivo</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p>Non ci sono obiettivi di gruppo attivi.</p>
    @endif

@endsection
