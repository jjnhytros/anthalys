@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.cooking') }}</h1>

    <form action="{{ route('cooking.prepare') }}" method="POST">
        @csrf
        <label for="ingredient">{{ __('messages.select_ingredient') }}</label>
        <select name="ingredient" class="form-control">
            @foreach ($ingredients as $ingredient)
                <option value="{{ $ingredient->item_name }}">{{ $ingredient->item_name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">{{ __('messages.cook') }}</button>
    </form>
@endsection
