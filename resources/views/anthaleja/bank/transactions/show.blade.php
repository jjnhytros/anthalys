<!-- resources/views/anthaleja/bank/transactions/show.blade.php -->

@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Dettagli della Transazione</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Mittente:</strong> {{ $transaction->sender->name ?? 'N/A' }}</p>
                        <p><strong>Destinatario:</strong> {{ $transaction->recipient->name ?? 'N/A' }}</p>
                        <p><strong>Importo:</strong> {{ athel($transaction->amount) }}</p>
                        <p><strong>Descrizione:</strong> {{ $transaction->description ?? 'N/A' }}</p>
                        <p><strong>Stato attuale:</strong>
                            <span
                                class="badge
                            @if ($transaction->status === 'pending') bg-warning
                            @elseif($transaction->status === 'confirmed') bg-primary
                            @elseif($transaction->status === 'approved') bg-success
                            @else bg-secondary @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </p>

                        <!-- Pulsante per confermare la transazione -->
                        @if ($transaction->status === 'pending' && $transaction->sender_id === Auth::user()->character->id)
                            <form method="POST" action="{{ route('transactions.confirm', $transaction->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    {!! getIcon('check-circle', 'bi', 'Conferma Transazione') !!}
                                </button>
                            </form>
                        @endif

                        <!-- Pulsante per approvare la transazione -->
                        @if ($transaction->status === 'confirmed' && $transaction->recipient_id === Auth::user()->character->id)
                            <form method="POST" action="{{ route('transactions.approve', $transaction->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    {!! getIcon('check-circle-fill', 'bi', 'Approva Transazione') !!}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
