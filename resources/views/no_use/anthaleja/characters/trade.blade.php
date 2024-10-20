@extends('layouts.main')

@section('content')
    <h1>Commercio di Risorse per {{ $character->name }}</h1>

    <h3>Scambia risorse con un altro personaggio</h3>

    <!-- Form per eseguire uno scambio di oggetti -->
    <form action="{{ route('characters.execute-trade', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="recipient_id">Destinatario</label>
            <select name="recipient_id" class="form-control" required>
                @foreach ($otherCharacters as $otherCharacter)
                    <option value="{{ $otherCharacter->id }}">{{ $otherCharacter->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="item_id">Oggetto da scambiare</label>
            <select name="item_id" class="form-control" required>
                @foreach ($character->inventories as $item)
                    <option value="{{ $item->id }}">{{ $item->item_name }} - Quantità: {{ $item->quantity }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Quantità da scambiare</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Scambia</button>
    </form>

    <!-- Messaggi di successo o errore -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
@endsection
