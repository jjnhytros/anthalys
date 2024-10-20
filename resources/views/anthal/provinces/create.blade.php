@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.create_province') }}</h1>

    <form action="{{ route('provinces.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="province" class="form-label">{{ __('messages.province_name') }}</label>
            <input type="text" class="form-control" id="province" name="province" value="{{ old('province') }}" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">{{ __('messages.full_name') }}</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}"
                required>
        </div>
        <div class="mb-3">
            <label for="form" class="form-label">{{ __('messages.form') }}</label>
            <input type="text" class="form-control" id="form" name="form" value="{{ old('form') }}" required>
        </div>
        <div class="mb-3">
            <label for="state" class="form-label">{{ __('messages.state') }}</label>
            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">{{ __('messages.color') }}</label>
            <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}" required>
        </div>
        <div class="mb-3">
            <label for="capital" class="form-label">{{ __('messages.capital') }} ({{ __('messages.optional') }})</label>
            <input type="text" class="form-control" id="capital" name="capital" value="{{ old('capital') }}">
        </div>
        <div class="mb-3">
            <label for="area_km2" class="form-label">{{ __('messages.area_km2') }}</label>
            <input type="number" class="form-control" id="area_km2" name="area_km2" value="{{ old('area_km2') }}"
                required>
        </div>
        <div class="mb-3">
            <label for="population_total" class="form-label">{{ __('messages.total_population') }}</label>
            <input type="number" class="form-control" id="population_total" name="population_total"
                value="{{ old('population_total') }}" required>
        </div>
        <div class="mb-3">
            <label for="population_rural" class="form-label">{{ __('messages.rural_population') }}</label>
            <input type="number" class="form-control" id="population_rural" name="population_rural"
                value="{{ old('population_rural') }}" required>
        </div>
        <div class="mb-3">
            <label for="population_urban" class="form-label">{{ __('messages.urban_population') }}</label>
            <input type="number" class="form-control" id="population_urban" name="population_urban"
                value="{{ old('population_urban') }}" required>
        </div>
        <div class="mb-3">
            <label for="burgs" class="form-label">{{ __('messages.burgs') }}</label>
            <input type="number" class="form-control" id="burgs" name="burgs" value="{{ old('burgs') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('messages.create_province') }}</button>
    </form>
@endsection
