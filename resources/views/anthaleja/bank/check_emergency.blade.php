@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Controllo Saldo di Emergenza</h2>
        <p>Il tuo saldo corrente è di {{ number_format($character->bank, 2) }} unità in banca.</p>

        <form action="{{ route('bank.check.emergency') }}" method="GET">
            <button type="submit" class="btn btn-warning">Verifica Saldo di Emergenza</button>
        </form>
    </div>
@endsection
