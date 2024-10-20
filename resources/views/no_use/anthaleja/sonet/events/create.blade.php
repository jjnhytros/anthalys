@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.create_event') }}</h1>

    <form action="{{ route('events.store', $group->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('messages.event_name') }}</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">{{ __('messages.event_description') }}</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="event_date" class="form-label">{{ __('messages.event_date') }}</label>
            <input type="datetime-local" name="event_date" id="event_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.create_event') }}</button>
    </form>
@endsection
