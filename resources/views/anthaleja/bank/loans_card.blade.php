@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Prestiti Attivi</h2>

        @if ($loans->count() > 0)
            <div class="row">
                @foreach ($loans as $loan)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Prestito di {{ number_format($loan->initial_amount, 2) }} unità</h5>
                                <p class="card-text">
                                    <strong>Saldo Residuo:</strong> {{ number_format($loan->loan_amount, 2) }} unità<br>
                                    <strong>Interessi Accumulati:</strong> {{ number_format($loan->loan_interest, 2) }}
                                    unità<br>
                                    <strong>Prossima Rata:</strong> {{ number_format($loan->loan_installment, 2) }}
                                    unità<br>
                                    <strong>Scadenza Prossima Rata:</strong>
                                    {{ Carbon::parse($loan->next_payment_due_date)->format('d/m/Y') }}<br>
                                    <strong>Data di Scadenza del Prestito:</strong>
                                    {{ Carbon::parse($loan->loan_due_date)->format('d/m/Y') }}
                                </p>
                                <a href="{{ route('bank.loan.repay') }}" class="btn btn-primary btn-sm">Rimborsa</a>
                                <a href="{{ route('bank.loan.extend') }}" class="btn btn-warning btn-sm">Estendi</a>
                                <a href="{{ route('bank.loan.full_repayment') }}" class="btn btn-danger btn-sm">Pag.
                                    Anticipato</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Non hai prestiti attivi.</p>
        @endif
    </div>
@endsection
