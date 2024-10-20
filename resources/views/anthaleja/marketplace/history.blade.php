@extends('layouts.main')

@section('content')
    <h1>Storico delle Transazioni</h1>

    <table>
        <thead>
            <tr>
                <th>Oggetto</th>
                <th>Prezzo</th>
                <th>Tipo</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->item->name }}</td>
                    <td>{{ athel($transaction->price) }}</td>
                    <td>{{ $transaction->buyer_id == auth()->user()->character->id ? 'Acquisto' : 'Vendita' }}</td>
                    <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
