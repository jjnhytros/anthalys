@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.daily_actions') }}</h1>

    <!-- Mangiare -->
    <form action="{{ route('daily_actions.eat') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">{{ __('messages.eat') }}</button>
    </form>

    <!-- Lavorare -->
    <form action="{{ route('daily_actions.work') }}" method="POST">
        @csrf
        <label for="hours">{{ __('messages.work_hours') }}</label>
        <input type="number" name="hours" value="8" min="1" max="12">
        <button type="submit" class="btn btn-primary">{{ __('messages.work') }}</button>
    </form>

    <!-- Pulire la casa -->
    <form action="{{ route('daily_actions.clean_house') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-warning">{{ __('messages.clean_house') }}</button>
    </form>
@endsection
