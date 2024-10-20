@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.travel_status') }}</h1>

    <p>{{ __('messages.traveling_from') }} {{ $travelLog->fromRegion->name }} {{ __('messages.to') }}
        {{ $travelLog->toRegion->name }}</p>
    <p>{{ __('messages.departure') }}: {{ $travelLog->departure_time }}</p>
    <p>{{ __('messages.expected_arrival') }}: {{ $travelLog->arrival_time }}</p>
    <p>{{ __('messages.weather_conditions') }}: {{ $travelLog->weather }}</p>

    <h3>{{ __('messages.character_current_status') }}:</h3>
    <ul>
        <li>{{ __('messages.energy') }}: {{ $character->energy }}/100</li>
        <li>{{ __('messages.happiness') }}: {{ $character->happiness }}/100</li>
        <li>{{ __('messages.hunger') }}: {{ $character->hunger }}/100</li>
        <li>{{ __('messages.cleanliness') }}: {{ $character->cleanliness }}/100</li>
        <li>{{ __('messages.cash') }}: {{ $character->cash }}</li>
        <li>{{ __('messages.bank_money') }}: {{ $character->bank }}</li>
    </ul>

    @if (now()->greaterThanOrEqualTo($travelLog->arrival_time))
        <p><strong>{{ __('messages.travel_completed') }}</strong></p>
    @else
        <p>{{ __('messages.travel_in_progress') }}</p>
        <form action="{{ route('travel.rest', $character->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning">{{ __('messages.rest_button') }}</button>
        </form>
        <!-- Opzione per consumare cibo -->
        <form action="{{ route('travel.eat', $character->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">{{ __('messages.eat_button') }}</button>
        </form>
    @endif
@endsection
