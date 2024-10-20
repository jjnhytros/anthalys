@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Estratto Conto</h2>

        <!-- Mostra il saldo disponibile al giorno corrente -->
        <p class="text-muted">Saldo disponibile al {{ now()->format('d/m/Y') }}: {{ number_format($currentBalance, 2) }}</p>

        <!-- Mostra il saldo medio mensile -->
        <p class="text-muted">Saldo medio mensile: {{ number_format($monthlyAverageBalance, 2) }}</p>

        <!-- Mostra il saldo medio annuale -->
        <p class="text-muted">Saldo medio annuale: {{ number_format($yearlyAverageBalance, 2) }}</p>

        <!-- Mostra la giacenza media annuale -->
        <p class="text-muted">Giacenza media annuale: {{ number_format($annualAverageBalance, 2) }}</p>

        <!-- Sezione Transazioni -->
        <h3>Transazioni</h3>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Destinatario/Mittente</th>
                    <th>Descrizione</th>
                    <th>Importo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filteredTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($transaction->sender_id == auth()->user()->character->id)
                                <!-- Se il personaggio è il mittente, mostra il destinatario -->
                                {{ $transaction->recipient->first_name }} {{ $transaction->recipient->last_name }}
                            @else
                                <!-- Se il personaggio è il destinatario, mostra il mittente -->
                                {{ $transaction->sender->first_name }} {{ $transaction->sender->last_name }}
                            @endif
                        </td>
                        <td>{{ $transaction->description ?? 'N/A' }}</td>
                        <td class="text-end">
                            @if ($transaction->sender_id == auth()->user()->character->id)
                                <!-- Se il personaggio è il mittente, visualizza l'importo come negativo (spesa) -->
                                <span class="text-danger">-{{ number_format($transaction->amount, 2) }}</span>
                            @else
                                <!-- Se il personaggio è il destinatario, visualizza l'importo come positivo (guadagno) -->
                                {{ number_format($transaction->amount, 2) }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Nessuna transazione disponibile.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        .container {
            max-width: 800px;
        }

        .text-danger {
            font-weight: bold;
        }
    </style>
@endsection
