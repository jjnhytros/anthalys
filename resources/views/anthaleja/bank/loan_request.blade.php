@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Richiesta di Prestito</h2>
        <form action="{{ route('bank.loan.apply') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="loan_amount" class="form-label">Importo del Prestito</label>
                <input type="number" name="loan_amount" id="loan_amount" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="loan_duration" class="form-label">Durata del Prestito (in mesi)</label>
                <input type="number" name="loan_duration" id="loan_duration" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Richiedi Prestito</button>
        </form>
    </div>
@endsection
