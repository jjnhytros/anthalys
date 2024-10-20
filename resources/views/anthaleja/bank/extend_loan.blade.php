@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Estensione del Prestito</h2>

        @if ($character->loan_amount > 0)
            <p>Saldo Prestito Attuale: {{ number_format($character->loan_amount, 2) }} unità</p>
            <p>Interessi Attuali: {{ number_format($character->loan_interest, 2) }} unità</p>
            <p>Data di Scadenza Attuale: {{ Carbon::parse($character->loan_due_date)->format('d/m/Y') }}</p>

            <!-- Form per estendere il prestito -->
            <form action="{{ route('bank.loan.extend') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="extension_months" class="form-label">Estensione (in mesi)</label>
                    <input type="number" name="extension_months" id="extension_months" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Estendi il Prestito</button>
            </form>
        @else
            <p>Non hai prestiti attivi da estendere.</p>
        @endif
    </div>
@endsection
