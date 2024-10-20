@extends('main.app')

@section('content')
    <h1>Aggiungi Transazione per {{ $character->name }}</h1>

    <form action="{{ route('characters.transactions.store', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="transaction_type">Tipo di Transazione</label>
            <select name="transaction_type" class="form-control" required>
                <option value="income">Entrata</option>
                <option value="expense">Uscita</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amount">Importo</label>
            <input type="number" name="amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Descrizione</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salva</button>
    </form>
@endsection
