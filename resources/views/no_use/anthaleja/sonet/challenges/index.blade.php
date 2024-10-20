@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.challenges') }}</h1>

    <h2>{{ __('messages.active_challenges') }}</h2>
    <ul class="list-group">
        @foreach ($challenges->where('status', 'active') as $challenge)
            <li class="list-group-item">
                {{ $challenge->creator->first_name }} vs {{ $challenge->opponent->first_name }} -
                {{ __('messages.challenge_type') }}: {{ $challenge->challenge_type }} -
                {{ __('messages.target') }}: {{ $challenge->target_value }}
                <div>{{ __('messages.progress') }}: {{ $challenge->progress['creator'] }} vs
                    {{ $challenge->progress['opponent'] }}</div>
                @if ($challenge->end_date)
                    <span>{{ __('messages.ends_on') }}: {{ $challenge->end_date->format('d/m/Y') }}</span>
                @endif
            </li>
        @endforeach
    </ul>

    <h2>{{ __('messages.pending_challenges') }}</h2>
    <ul class="list-group">
        @foreach ($challenges->where('status', 'pending') as $challenge)
            <li class="list-group-item">
                {{ $challenge->creator->first_name }} {{ __('messages.challenged') }}
                {{ $challenge->opponent->first_name }}
                <form action="{{ route('challenges.accept', $challenge->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">{{ __('messages.accept') }}</button>
                </form>
                <form action="{{ route('challenges.decline', $challenge->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger btn-sm">{{ __('messages.decline') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
