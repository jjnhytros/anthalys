@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h1>{{ __('messages.welcome_message') }}</h1>
                <p class="lead">{{ __('messages.welcome_description') }}</p>
            </div>

            <!-- Province Summary -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        {{ __('messages.province_summary') }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.total_provinces') }}: {{ $totalProvinces }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ __('messages.deleted_provinces') }}:
                            {{ $deletedProvinces }}</h6>
                        <p class="card-text">
                            {{ __('messages.manage_provinces_description') }}
                        </p>
                        <a href="{{ route('provinces.index') }}"
                            class="btn btn-primary">{{ __('messages.manage_provinces_button') }}</a>
                    </div>
                </div>
            </div>

            <!-- Timezone Summary -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        {{ __('messages.timezone_summary') }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.total_timezones') }}: {{ $totalTimezones }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ __('messages.deleted_timezones') }}:
                            {{ $deletedTimezones }}</h6>
                        <p class="card-text">
                            {{ __('messages.manage_timezones_description') }}
                        </p>
                        <a href="{{ route('timezones.index') }}"
                            class="btn btn-info">{{ __('messages.manage_timezones_button') }}</a>
                    </div>
                </div>
            </div>

            <!-- Month Summary -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">{{ __('messages.month_summary') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.total_months') }}: {{ $totalMonths }}</h5>
                        <p class="card-text">
                            @if ($totalMonths > 0)
                                <ul>
                                    {{ $months }}
                                </ul>
                            @else
                                {{ __('messages.no_months_available') }}
                            @endif
                        </p>
                        <p class="card-text">{{ __('messages.view_months_description') }}</p>
                        <a href="{{ route('day_months.index') }}"
                            class="btn btn-primary">{{ __('messages.manage_months_button') }}</a>
                    </div>
                </div>
            </div>

            <!-- Day Summary -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">{{ __('messages.day_summary') }}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.total_days') }}: {{ $totalDays }}</h5>
                        <p class="card-text">
                            @if ($totalDays > 0)
                                <ul>
                                    {{ $days }}
                                </ul>
                            @else
                                {{ __('messages.no_days_available') }}
                            @endif
                        </p>
                        <p class="card-text">{{ __('messages.view_days_description') }}</p>
                        <a href="{{ route('day_months.index') }}"
                            class="btn btn-primary">{{ __('messages.manage_days_button') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-header">
                    {{ __('messages.quick_actions') }}
                </div>
                <div class="card-body">
                    <a href="{{ route('provinces.create') }}"
                        class="btn btn-success mb-2">{{ __('messages.add_new_province') }}</a>
                    <a href="{{ route('timezones.create') }}"
                        class="btn btn-info mb-2">{{ __('messages.add_new_timezone') }}</a>
                    <a href="{{ route('provinces.index', ['deleted' => true]) }}"
                        class="btn btn-warning mb-2">{{ __('messages.view_deleted_provinces') }}</a>
                    <a href="{{ route('timezones.index', ['deleted' => true]) }}"
                        class="btn btn-warning mb-2">{{ __('messages.view_deleted_timezones') }}</a>
                </div>
            </div>
        </div>

        <!-- Additional Sections or Reports (Optional) -->
        <div class="col-md-12 mt-4">
            <div class="alert alert-info">
                <h4>{{ __('messages.reports_analytics') }}</h4>
                <p>{{ __('messages.reports_description') }}</p>
                <a href="#" class="btn btn-secondary">{{ __('messages.view_reports') }}</a>
            </div>
        </div>
    </div>
@endsection
