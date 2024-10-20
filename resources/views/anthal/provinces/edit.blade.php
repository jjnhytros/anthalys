@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.edit_province') }}</h1>

    <form action="{{ route('provinces.update', $province->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="province" class="form-label">{{ __('messages.province_name') }}</label>
            <input type="text" class="form-control" id="province" name="province" value="{{ $province->province }}" required>
        </div>
        <div class="mb-3">
            <label for="full_name" class="form-label">{{ __('messages.full_name') }}</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $province->full_name }}"
                required>
        </div>
        <div class="mb-3">
            <label for="form" class="form-label">{{ __('messages.form') }}</label>
            <input type="text" class="form-control" id="form" name="form" value="{{ $province->form }}" required>
        </div>
        <div class="mb-3">
            <label for="state" class="form-label">{{ __('messages.state') }}</label>
            <input type="text" class="form-control" id="state" name="state" value="{{ $province->state }}"
                required>
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">{{ __('messages.color') }}</label>
            <input type="text" class="form-control" id="color" name="color" value="{{ $province->color }}"
                required>
        </div>
        <div class="mb-3">
            <label for="capital" class="form-label">{{ __('messages.capital') }} ({{ __('messages.optional') }})</label>
            <input type="text" class="form-control" id="capital" name="capital" value="{{ $province->capital }}">
        </div>
        <div class="mb-3">
            <label for="area_km2" class="form-label">{{ __('messages.area_km2') }}</label>
            <input type="number" class="form-control" id="area_km2" name="area_km2" value="{{ $province->area_km2 }}"
                required>
        </div>
        <div class="mb-3">
            <label for="population_total" class="form-label">{{ __('messages.total_population') }}</label>
            <input type="number" class="form-control" id="population_total" name="population_total"
                value="{{ $province->population_total }}" required>
        </div>
        <div class="mb-3">
            <label for="population_rural" class="form-label">{{ __('messages.rural_population') }}</label>
            <input type="number" class="form-control" id="population_rural" name="population_rural"
                value="{{ $province->population_rural }}" required>
        </div>
        <div class="mb-3">
            <label for="population_urban" class="form-label">{{ __('messages.urban_population') }}</label>
            <input type="number" class="form-control" id="population_urban" name="population_urban"
                value="{{ $province->population_urban }}" required>
        </div>
        <div class="mb-3">
            <label for="burgs" class="form-label">{{ __('messages.burgs') }}</label>
            <input type="number" class="form-control" id="burgs" name="burgs" value="{{ $province->burgs }}"
                required>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('messages.update_province') }}</button>
    </form>
@endsection
