@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.shopping') }}</h1>

    <form action="{{ route('shopping.buy') }}" method="POST">
        @csrf
        <label for="item_id">{{ __('messages.select_item') }}</label>
        <select name="item_id" class="form-control">
            @foreach ($items as $item)
                <option value="{{ $item->id }}">{{ $item->resource->name }} - {{ $item->price }}
                    {{ __('messages.currency') }}</option>
            @endforeach
        </select>

        <label for="quantity">{{ __('messages.quantity') }}</label>
        <input type="number" name="quantity" class="form-control" min="1">

        <button type="submit" class="btn btn-primary">{{ __('messages.buy') }}</button>
    </form>
@endsection
