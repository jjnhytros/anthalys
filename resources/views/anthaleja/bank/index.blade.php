@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Benvenuto nella Banca</h2>

        <div class="row mt-4">
            <div class="col-md-3">
                <a href="{{ route('bank.withdraw') }}" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-arrow-down-circle"></i> Prelievo
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('bank.deposit') }}" class="btn btn-success w-100 mb-3">
                    <i class="bi bi-arrow-up-circle"></i> Versamento
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('bank.transfer') }}" class="btn btn-warning w-100 mb-3">
                    <i class="bi bi-send"></i> Bonifico
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('bank.statement') }}" class="btn btn-info w-100 mb-3">
                    <i class="bi bi-file-earmark-text"></i> Estratto Conto
                </a>
            </div>

            <div class="col-md-4 mb-4">
                <a href="{{ route('bank.loans') }}" class="btn btn-primary btn-block">
                    <i class="bi bi-wallet2"></i> Gestione Prestiti
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('bank.other-operations') }}" class="btn btn-secondary w-100 mb-3">
                    <i class="bi bi-tools"></i> Altre Operazioni
                </a>
            </div>
        </div>
    </div>
@endsection
