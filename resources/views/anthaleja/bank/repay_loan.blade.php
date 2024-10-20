@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Rimborso Prestito</h2>

        @if ($character->loan_amount > 0)
            <p>Saldo Prestito: {{ number_format($character->loan_amount, 2) }} unità</p>
            <p>Interessi Accumulati: {{ number_format($character->loan_interest, 2) }} unità</p>
            <p>Data di Scadenza Attuale: {{ Carbon::parse($character->loan_due_date)->format('d/m/Y') }}</p>

            <!-- Form per il rimborso parziale -->
            <form action="{{ route('bank.loan.repay') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="repay_amount" class="form-label">Importo da Rimborsare</label>
                    <input type="number" name="repay_amount" id="repay_amount" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Rimborsa</button>
            </form>

            <!-- Form per l'estensione del prestito -->
            <form action="{{ route('bank.loan.extend') }}" method="POST" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="extension_months" class="form-label">Estensione (in mesi)</label>
                    <input type="number" name="extension_months" id="extension_months" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning">Estendi il Prestito</button>
            </form>

            <!-- Form per il rimborso completo anticipato -->
            <form action="{{ route('bank.loan.repay') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="full_repayment" value="1">
                <p>Puoi anche saldare l'intero prestito ora, per un totale di
                    {{ number_format($character->loan_amount + $character->loan_interest, 2) }} unità.</p>
                <button type="submit" class="btn btn-danger">Pagamento Anticipato Completo</button>
            </form>
        @else
            <p>Non hai prestiti attivi.</p>
        @endif
    </div>
@endsection
