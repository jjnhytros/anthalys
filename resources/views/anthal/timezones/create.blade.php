@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.create_timezone') }}</h1>
    <form action="{{ route('timezones.store') }}" method="POST">
        @csrf
        <label>{{ __('messages.identifier') }}:</label>
        <input type="text" name="identifier" required>

        <label>{{ __('messages.latitude') }}:</label>
        <input type="text" name="latitude" required>

        <label>{{ __('messages.longitude') }}:</label>
        <input type="text" name="longitude" required>

        <label>{{ __('messages.comments') }}:</label>
        <textarea name="comments" required></textarea>

        <button type="submit">{{ __('messages.create') }}</button>
    </form>
@endsection
