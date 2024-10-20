@extends('main.app')

@section('content')
    <h1>Transazioni di {{ $character->name }}</h1>

    <a href="{{ route('characters.transactions.create', $character) }}" class="btn btn-primary">Aggiungi Transazione</a>

    <ul>
        @foreach ($transactions as $transaction)
            <li>
                <strong>{{ ucfirst($transaction->transaction_type) }}</strong> - {{ $transaction->amount }} $
                <p>{{ $transaction->description }}</p>
            </li>
        @endforeach
    </ul>
@endsection
