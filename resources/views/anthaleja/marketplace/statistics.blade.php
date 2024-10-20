@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 class="mb-4">Statistiche del Marketplace</h1>

        {{-- Sezione per le statistiche delle fluttuazioni dei prezzi --}}
        <div class="card mb-4">
            <div class="card-header">
                <h2>Fluttuazioni dei Prezzi</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Oggetto</th>
                            <th>Prezzo Attuale</th>
                            <th>Variazione Giornaliera (%)</th>
                            <th>Variazione Settimanale (%)</th>
                            <th>Variazione Mensile (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ number_format($item->price, 2) }} Athel</td>
                                <td class="{{ $item->daily_change >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $item->daily_change >= 0 ? '+' : '' }}{{ number_format($item->daily_change, 2) }}%
                                </td>
                                <td class="{{ $item->weekly_change >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $item->weekly_change >= 0 ? '+' : '' }}{{ number_format($item->weekly_change, 2) }}%
                                </td>
                                <td class="{{ $item->monthly_change >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $item->monthly_change >= 0 ? '+' : '' }}{{ number_format($item->monthly_change, 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sezione per la disponibilità delle risorse --}}
        <div class="card mb-4">
            <div class="card-header">
                <h2>Disponibilità delle Risorse</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Risorsa</th>
                            <th>Quantità Disponibile</th>
                            <th>Regione</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resources as $resource)
                            <tr>
                                <td>{{ $resource->type }}</td>
                                <td>{{ number_format($resource->amount, 2) }}</td>
                                <td>{{ $resource->region->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sezione per gli eventi del marketplace --}}
        <div class="card">
            <div class="card-header">
                <h2>Eventi del Marketplace</h2>
            </div>
            <div class="card-body">
                @if ($events->isEmpty())
                    <p>Al momento non ci sono eventi attivi.</p>
                @else
                    <ul class="list-group">
                        @foreach ($events as $event)
                            <li class="list-group-item">
                                <strong>{{ $event->name }}</strong> <br>
                                Dal {{ $event->start_time->format('d/m/Y') }} al {{ $event->end_time->format('d/m/Y') }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
