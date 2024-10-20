@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Prelievo</h2>

        <form action="{{ route('bank.withdraw') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="withdraw_amount">Importo da prelevare</label>
                <input type="number" name="withdraw_amount" id="withdraw_amount" class="form-control" required min="1"
                    max="{{ Auth::user()->character->bank }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Preleva</button>
        </form>

        <p class="mt-3">Saldo attuale: {{ Auth::user()->character->cash }}</p>
        <p>Saldo in banca: {{ Auth::user()->character->bank }}</p>
    </div>
@endsection
