@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.edit_timezone') }}</h1>
    <form action="{{ route('timezones.update', $timezone->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>{{ __('messages.identifier') }}:</label>
        <input type="text" name="identifier" value="{{ $timezone->identifier }}" required>

        <label>{{ __('messages.latitude') }}:</label>
        <input type="text" name="latitude" value="{{ $timezone->latitude }}" required>

        <label>{{ __('messages.longitude') }}:</label>
        <input type="text" name="longitude" value="{{ $timezone->longitude }}" required>

        <label>{{ __('messages.comments') }}:</label>
        <textarea name="comments" required>{{ $timezone->comments }}</textarea>

        <button type="submit">{{ __('messages.update') }}</button>
    </form>
@endsection
