@extends('layouts.main')

@section('content')
    <div class="container">
        <h2>{{ __('Notification Settings') }}</h2>

        <form action="{{ route('preferences.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Itera sui tipi di notifiche dinamicamente -->
            @foreach ($notificationTypes as $type)
                <div class="form-group mb-3">
                    <label for="{{ $type }}_notification">
                        {{ __('Notify me for ') . ucwords(str_replace('_', ' ', $type)) }}
                    </label>
                    <input type="checkbox" name="{{ $type }}_notification" id="{{ $type }}_notification"
                        value="1"
                        {{ isset($preferences[$type . '_notification']) && $preferences[$type . '_notification'] ? 'checked' : '' }}>
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary">{{ __('Save Preferences') }}</button>
        </form>
    </div>
@endsection
