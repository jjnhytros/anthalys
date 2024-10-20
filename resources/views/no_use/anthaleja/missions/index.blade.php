@extends('layouts.main')

@section('content')
    <h1>Missioni di Lavoro</h1>

    <h2>Missioni Attive</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Obiettivo</th>
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

    <h3>Assegna Nuova Missione</h3>

    <form action="{{ route('missions.assign') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="work_mission_id">Seleziona una Missione</label>
            <select name="work_mission_id" id="work_mission_id" class="form-control">
                @foreach ($availableMissions as $mission)
                    <option value="{{ $mission->id }}">{{ $mission->objective }} ({{ $mission->reward_amount }}
                        {{ $mission->reward_type }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assegna Missione</button>
    </form>
@endsection
