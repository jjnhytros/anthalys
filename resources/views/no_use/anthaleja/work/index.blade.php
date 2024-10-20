@extends('layouts.main')

@section('content')
    <h1>Gestione del Lavoro</h1>

    <h2>Informazioni sul Personaggio</h2>
    <ul>
        <li>Salario attuale: {{ $character->profession->salary }}</li>
        <li>Esperienza lavorativa: {{ $character->work_experience }}</li>
        <li>Livello di lavoro: {{ $character->work_level }}</li>
        <li>Fedeltà alla professione: {{ $character->loyalty }}</li>
        <li>Livello di felicità: {{ $character->happiness }}</li>
    </ul>

    <h3>Missioni Attive</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Missione</th>
                <th>Progresso</th>
                <th>Ricompensa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($missions as $mission)
                <tr>
                    <td>{{ $mission->workMission->objective }}</td>
                    <td>{{ $mission->progress }} / {{ $mission->workMission->target }}</td>
                    <td>{{ ucfirst($mission->workMission->reward_type) }}: {{ $mission->workMission->reward_amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('work.start') }}" method="POST">
        @csrf
        <label for="hours">Ore di lavoro</label>
        <input type="number" name="hours" value="8" min="1" max="12" class="form-control">
        <button type="submit" class="btn btn-primary mt-2">Inizia Lavoro</button>
    </form>
@endsection
