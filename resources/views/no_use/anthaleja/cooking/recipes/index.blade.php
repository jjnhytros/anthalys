@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.cooking_recipes') }}</h1>

    <form action="{{ route('cooking.cook', $character->id) }}" method="POST">
        @csrf
        <label for="recipe">{{ __('messages.select_recipe') }}</label>
        <select name="recipe_id" class="form-control">
            @foreach ($recipes as $recipe)
                <option value="{{ $recipe->id }}">
                    {{ $recipe->name }} ({{ $recipe->ingredients->pluck('name')->join(', ') }})
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">{{ __('messages.cook_recipe') }}</button>
    </form>
@endsection
