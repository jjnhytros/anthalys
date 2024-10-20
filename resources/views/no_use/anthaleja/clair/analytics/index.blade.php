@extends('layouts.main')

@section('content')
    <h1>Metriche delle Interazioni</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Interazione</th>
                <th>Tempo di Risposta (ms)</th>
                <th>Fonti Utilizzate</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($analytics as $item)
                <tr>
                    <td>{{ $item->interaction->message }}</td>
                    <td>{{ $item->response_time }}</td>
                    <td>{{ $item->source_usage_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
