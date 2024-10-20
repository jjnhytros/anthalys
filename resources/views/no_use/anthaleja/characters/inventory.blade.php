@extends('layouts.main')

@section('content')
    <h1>Inventario di {{ $character->name }}</h1>

    <!-- Mostra gli oggetti nell'inventario -->
    @if ($inventory->count())
        <ul>
            @foreach ($inventory as $item)
                <li>
                    <strong>{{ $item->item_name }}</strong> - Quantità: {{ $item->quantity }} - Valore: {{ $item->value }}
                    $
                    <form action="{{ route('characters.use-item', [$character, $item]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Usa</button>
                    </form>
                    <form action="{{ route('characters.sell-item', [$character, $item]) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Vendi</button>
                    </form>
                </li>
            @endforeach
        </ul>
        <h1>{{ __('messages.inventory') }}</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.item_name') }}</th>
                    <th>{{ __('messages.quantity') }}</th>
                    <th>{{ __('messages.freshness') }}</th>
                    <th>{{ __('messages.expiration_date') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventory as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->freshness }}%</td>
                        <td>{{ $item->expiration_date ? $item->expiration_date->format('Y-m-d') : __('messages.no_expiration') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>L'inventario è vuoto.</p>
    @endif
    <h3>{{ __('messages.inventory_fridge') }}</h3>
    <ul>
        @foreach ($fridgeItems as $item)
            <li>{{ $item->name }} ({{ $item->condition }})</li>
        @endforeach
    </ul>

    <h3>{{ __('messages.inventory_pantry') }}</h3>
    <ul>
        @foreach ($pantryItems as $item)
            <li>{{ $item->name }} ({{ $item->condition }})</li>
        @endforeach
    </ul>

    <!-- Aggiungi nuovo oggetto -->
    <h3>Aggiungi un nuovo oggetto</h3>
    <form action="{{ route('characters.add-item', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="item_name">Nome dell'oggetto</label>
            <input type="text" name="item_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantità</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="value">Valore (per unità)</label>
            <input type="number" name="value" class="form-control" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi</button>
    </form>
@endsection
