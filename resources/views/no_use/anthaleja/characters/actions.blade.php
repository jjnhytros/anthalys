@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.daily_actions') }}</h1>

    <ul>
        <li>{{ __('messages.energy') }}: {{ $character->energy }}/100</li>
        <li>{{ __('messages.happiness') }}: {{ $character->happiness }}/100</li>
        <li>{{ __('messages.hunger') }}: {{ $character->hunger }}/100</li>
        <li>{{ __('messages.cash') }}: {{ $character->cash }}</li>
    </ul>

    <form action="{{ route('character.eat', $character->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">{{ __('messages.eat') }}</button>
    </form>

    <form action="{{ route('character.work', $character->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary">{{ __('messages.work') }}</button>
    </form>

    <form action="{{ route('character.clean', $character->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-info">{{ __('messages.clean_house') }}</button>
    </form>

    <form action="{{ route('character.rest', $character->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-warning">{{ __('messages.rest') }}</button>
    </form>
@endsection
