@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.create_recipe') }}</h1>

    <form action="{{ route('recipes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">{{ __('messages.recipe_name') }}</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">{{ __('messages.description') }}</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="effects">{{ __('messages.effects') }}</label>
            <input type="text" name="effects" class="form-control">
        </div>

        <div class="form-group">
            <label for="ingredients">{{ __('messages.ingredients') }}</label>
            <select name="ingredients[]" class="form-control" multiple>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantities">{{ __('messages.quantities') }}</label>
            <input type="text" name="quantities[]" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.create_recipe') }}</button>
    </form>
@endsection
