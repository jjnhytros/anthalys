@extends('layouts.main')

@section('content')
    <h1>{{ $province->province }} {{ __('messages.details') }}</h1>
    <ul>
        <li><strong>{{ __('messages.full_name') }}:</strong> {{ $province->full_name }}</li>
        <li><strong>{{ __('messages.form') }}:</strong> {{ $province->form }}</li>
        <li><strong>{{ __('messages.state') }}:</strong> {{ $province->state }}</li>
        <li><strong>{{ __('messages.capital') }}:</strong> {{ $province->capital }}</li>
        <li><strong>{{ __('messages.area_km2') }}:</strong> {{ $province->area_km2 }}</li>
        <li><strong>{{ __('messages.total_population') }}:</strong> {{ $province->population_total }}</li>
        <li><strong>{{ __('messages.rural_population') }}:</strong> {{ $province->population_rural }}</li>
        <li><strong>{{ __('messages.urban_population') }}:</strong> {{ $province->population_urban }}</li>
        <li><strong>{{ __('messages.burgs') }}:</strong> {{ $province->burgs }}</li>
    </ul>

    <a href="{{ route('provinces.index') }}" class="btn btn-secondary">{{ __('messages.back_to_list') }}</a>
@endsection
