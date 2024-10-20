@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.edit_profile') }}</h1>

    <form action="{{ route('profiles.update', $profile->character->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="bio" class="form-label">{{ __('messages.bio') }}</label>
            <textarea class="form-control" id="bio" name="bio">{{ old('bio', $profile->bio) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="profile_picture" class="form-label">{{ __('messages.profile_picture') }}</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
        </div>

        <div class="mb-3">
            <label for="link" class="form-label">{{ __('messages.link') }}</label>
            <input type="url" class="form-control" id="link" name="link"
                value="{{ old('link', $profile->link) }}">
        </div>

        <div class="mb-3">
            <label for="privacy" class="form-label">{{ __('messages.privacy') }}</label>
            <select class="form-select" id="privacy" name="privacy">
                <option value="public" {{ $profile->privacy == 'public' ? 'selected' : '' }}>{{ __('messages.public') }}
                </option>
                <option value="private" {{ $profile->privacy == 'private' ? 'selected' : '' }}>
                    {{ __('messages.private') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ __('messages.save_changes') }}</button>
    </form>
@endsection
