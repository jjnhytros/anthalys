@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.events_in_group', ['group' => $group->name]) }}</h1>

    <a href="{{ route('events.create', $group->id) }}" class="btn btn-primary mb-4">{{ __('messages.create_event') }}</a>

    <ul class="list-group">
        @foreach ($events as $event)
            <li class="list-group-item">
                <h5>{{ $event->name }}</h5>
                <p>{{ $event->description }}</p>
                <p><strong>{{ __('messages.event_date') }}:</strong> {{ $event->event_date }}</p>

                <form action="{{ route('events.rsvp', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        @if ($event->participants->contains(Auth::user()->character->id))
                            {{ __('messages.cancel_rsvp') }}
                        @else
                            {{ __('messages.join_event') }}
                        @endif
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
