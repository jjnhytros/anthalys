@extends('layouts.main')

@section('content')
    <h1>{{ $character->first_name }} {{ $character->last_name }}
        @if ($character->profile->verified)
            <span class="badge bg-primary">{{ __('messages.verified') }}</span>
        @endif
    </h1>
    <img src="{{ $profile->profile_picture }}" alt="Profile Picture" class="img-thumbnail">
    @if (
        $profile->privacy == 'public' ||
            $profile->character->connections->contains('connected_character_id', auth()->user()->characters->first()->id))
        <p>{{ $profile->bio }}</p>
        <p><a href="{{ $profile->link }}" target="_blank">{{ $profile->link }}</a></p>
    @else
        <p>{{ __('messages.private_profile') }}</p>
    @endif
    <p><strong>{{ __('messages.privacy') }}:</strong> {{ $profile->privacy }}</p>
    <p><strong>{{ __('messages.verified') }}:</strong> {{ $profile->verified ? __('Yes') : __('No') }}</p>
    <p><a href="{{ $profile->link }}" target="_blank">{{ $profile->link }}</a></p>

    @if (auth()->user()->characters->contains($profile->character))
        <a href="{{ route('profiles.edit', $profile->character->id) }}"
            class="btn btn-primary">{{ __('messages.edit_profile') }}</a>
    @endif
@endsection
