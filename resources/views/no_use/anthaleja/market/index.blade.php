@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.market') }}</h1>

    <form action="{{ route('market.purchase', $character->id) }}" method="POST">
        @csrf
        <label for="market_item">{{ __('messages.select_item') }}</label>
        <select name="market_item_id" class="form-control">
            @foreach ($marketItems as $item)
                <option value="{{ $item->id }}">
                    {{ $item->resource->name }} - {{ $item->price }} {{ __('messages.currency') }}
                    ({{ $item->availability }} {{ __('messages.available') }})
                </option>
            @endforeach
        </select>

        <label for="quantity">{{ __('messages.quantity') }}</label>
        <input type="number" name="quantity" class="form-control" min="1">

        <button type="submit" class="btn btn-primary">{{ __('messages.purchase') }}</button>
    </form>
@endsection
