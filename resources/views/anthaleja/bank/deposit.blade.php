@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Deposito</h2>

        <form action="{{ route('bank.deposit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="deposit_amount">Importo da depositare</label>
                <input type="number" name="deposit_amount" id="deposit_amount" class="form-control" required min="1"
                    max="{{ Auth::user()->character->cash }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Deposita</button>
        </form>

        <p class="mt-3">Saldo attuale: {{ Auth::user()->character->cash }}</p>
        <p>Saldo in banca: {{ Auth::user()->character->bank }}</p>
    </div>
@endsection
