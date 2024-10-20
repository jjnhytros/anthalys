@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Prestiti Attivi</h2>

        <!-- Navbar con ricerca, filtro e selezione layout -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <form action="{{ route('bank.loans') }}" method="GET" class="d-flex me-auto">
                    <input type="text" name="search" placeholder="Cerca prestiti" class="form-control me-2">
                    <select name="filter" class="form-select me-2">
                        <option value="all">Tutti</option>
                        <option value="active">Attivi</option>
                        <option value="paid">Pagati</option>
                        <option value="overdue">In ritardo</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filtra</button>
                </form>

                <!-- Selettore del layout -->
                <div class="btn-group" role="group" aria-label="Seleziona layout">
                    <a href="{{ route('bank.loans', ['layout' => 'table']) }}"
                        class="btn btn-outline-secondary {{ request('layout') == 'table' ? 'active' : '' }}">Tabella</a>
                    <a href="{{ route('bank.loans', ['layout' => 'card']) }}"
                        class="btn btn-outline-secondary {{ request('layout') == 'card' ? 'active' : '' }}">Card</a>
                </div>
            </div>
        </nav>

        <!-- Mostra la visualizzazione selezionata -->
        @if (request('layout') == 'card' || !request('layout'))
            <!-- Visualizzazione Card -->
            @if ($loans->count() > 0)
                <div class="row">
                    @foreach ($loans as $loan)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Prestito di {{ number_format($loan->initial_amount, 2) }} unità
                                    </h5>
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
        @elseif(request('layout') == 'table')
            <!-- Visualizzazione Tabella -->
            @if ($loans->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Importo Totale</th>
                            <th>Saldo Residuo</th>
                            <th>Interessi</th>
                            <th>Data di Scadenza</th>
                            <th>Prossima Rata</th>
                            <th>Opzioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                                <td>{{ number_format($loan->initial_amount, 2) }} unità</td>
                                <td>{{ number_format($loan->loan_amount, 2) }} unità</td>
                                <td>{{ number_format($loan->loan_interest, 2) }} unità</td>
                                <td>{{ Carbon::parse($loan->loan_due_date)->format('d/m/Y') }}</td>
                                <td>{{ number_format($loan->loan_installment, 2) }} unità (scadenza:
                                    {{ Carbon::parse($loan->next_payment_due_date)->format('d/m/Y') }})</td>
                                <td>
                                    <a href="{{ route('bank.loan.repay') }}" class="btn btn-primary btn-sm">Rimborsa</a>
                                    <a href="{{ route('bank.loan.extend') }}" class="btn btn-warning btn-sm">Estendi</a>
                                    <a href="{{ route('bank.loan.full_repayment') }}" class="btn btn-danger btn-sm">Pag.
                                        Anticipato</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Non hai prestiti attivi.</p>
            @endif
        @endif
    </div>
@endsection
