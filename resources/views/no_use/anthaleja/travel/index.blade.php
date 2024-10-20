@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.travel_to_another_region') }}</h1>

    <form action="{{ route('travel.start', $character->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="to_region">{{ __('messages.select_region') }}</label>
            <select name="to_region_id" class="form-control">
                @foreach ($regions as $region)
                    <option value="{{ $region->id }}">
                        {{ $region->name }} ({{ __('messages.cost') }}: {{ $region->travel_cost }}
                        {{ __('messages.energy') }},
                        {{ $region->travel_cost * 10 }} {{ __('messages.money') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="travel_mode">{{ __('messages.travel_mode') }}</label>
            <select name="travel_mode" class="form-control">
                <option value="normal">{{ __('messages.normal') }}</option>
                <option value="fast">{{ __('messages.fast') }} ({{ __('messages.more_expensive') }})</option>
                <option value="slow">{{ __('messages.slow') }} ({{ __('messages.less_expensive') }})</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.start_journey') }}</button>
    </form>
@endsection
