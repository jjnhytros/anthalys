@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>I Miei Investimenti</h1>

        <!-- Sezione per investimenti attivi -->
        <h3>Investimenti Attivi</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Importo</th>
                        <th>Rendimento (%)</th>
                        <th>Durata (Giorni)</th>
                        <th>Data di Inizio</th>
                        <th>Stato</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeInvestments as $investment)
                        <tr>
                            <td>{{ ucfirst($investment->type) }}</td>
                            <td>{{ athel($investment->amount) }}</td>
                            <td>{{ number_format($investment->return_rate * 100, 2) }}%</td>
                            <td>{{ $investment->duration }}</td>
                            <td>{{ $investment->created_at->format('d-m-Y') }}</td>
                            <td>{{ ucfirst($investment->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sezione per investimenti completati -->
        <h3 class="mt-4">Storico Investimenti</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Importo</th>
                        <th>Rendimento (%)</th>
                        <th>Durata (Giorni)</th>
                        <th>Data di Inizio</th>
                        <th>Data di Completamento</th>
                        <th>Risultato</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completedInvestments as $investment)
                        <tr>
                            <td>{{ ucfirst($investment->type) }}</td>
                            <td>{{ athel($investment->amount) }}</td>
                            <td>{{ number_format($investment->return_rate * 100, 2) }}%</td>
                            <td>{{ $investment->duration }}</td>
                            <td>{{ $investment->created_at->format('d-m-Y') }}</td>
                            <td>{{ $investment->completed_at->format('d-m-Y') }}</td>
                            <td>{{ $investment->status == 'completed' ? 'Successo' : 'Fallimento' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
