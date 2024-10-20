@extends('layouts.main')

@section('content')
    <h1>Missioni Sociali per {{ $character->name }}</h1>

    <h3>Assegna una nuova missione</h3>

    <form action="{{ route('characters.assign-mission', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="related_character_id">Personaggio Coinvolto</label>
            <select name="related_character_id" class="form-control">
                @foreach ($otherCharacters as $otherCharacter)
                    <option value="{{ $otherCharacter->id }}">{{ $otherCharacter->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="mission_type">Tipo di Missione</label>
            <input type="text" name="mission_type" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Descrizione della Missione</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label for="reward_happiness">Ricompensa Felicità</label>
            <input type="number" name="reward_happiness" class="form-control" min="0" required>
        </div>

        <div class="form-group">
            <label for="reward_money">Ricompensa Monetaria</label>
            <input type="number" name="reward_money" class="form-control" min="0" required>
        </div>

        <button type="submit" class="btn btn-primary">Assegna Missione</button>
    </form>

    <!-- Elenco delle missioni attive -->
    <h3>Missioni Attive</h3>

    @if ($character->socialMissions->count())
        <ul>
            @foreach ($character->socialMissions as $mission)
                <li>
                    <strong>{{ $mission->mission_type }}</strong>: {{ $mission->description }}
                    - Felicità: +{{ $mission->reward_happiness }}, Denaro: +{{ $mission->reward_money }}
                    <form action="{{ route('characters.complete-mission', $mission) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Completa Missione</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p>Non ci sono missioni attive.</p>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
@endsection
