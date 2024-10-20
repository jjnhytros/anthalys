@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>Crea un nuovo investimento</h1>

        <form action="{{ route('investments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">Importo da investire</label>
                <input type="number" name="amount" id="amount" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Tipo di investimento</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="low_risk">Basso rischio</option>
                    <option value="medium_risk">Medio rischio</option>
                    <option value="high_risk">Alto rischio</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Crea Investimento</button>
        </form>
    </div>
@endsection
