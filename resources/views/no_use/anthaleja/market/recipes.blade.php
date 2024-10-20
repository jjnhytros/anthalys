@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.recipe_market') }}</h1>

    <form action="{{ route('market.buyRecipe', $market->id) }}" method="POST">
        @csrf
        <label for="recipe_market">{{ __('messages.select_recipe') }}</label>
        <select name="recipe_market_id" class="form-control">
            @foreach ($market->recipeMarkets as $recipeMarket)
                <option value="{{ $recipeMarket->id }}">
                    {{ $recipeMarket->recipe->name }} - {{ $recipeMarket->price }}$
                    ({{ $recipeMarket->availability }} {{ __('messages.available') }})
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">{{ __('messages.buy_recipe') }}</button>
    </form>
@endsection
