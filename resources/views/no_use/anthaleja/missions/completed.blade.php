@extends('layouts.main')

@section('content')
    <h1>Cronologia Missioni Completate</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Missione</th>
                <th>Ricompensa</th>
                <th>Data di Completamento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($completedMissions as $mission)
                <tr>
                    <td>{{ $mission->workMission->objective }}</td>
                    <td>{{ ucfirst($mission->workMission->reward_type) }}: {{ $mission->workMission->reward_amount }}</td>
                    <td>{{ $mission->updated_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
