@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.create_story') }}</h1>

    <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="media" class="form-label">{{ __('messages.upload_media') }}</label>
            <input type="file" class="form-control" id="media" name="media" required>
        </div>

        <div class="mb-3">
            <label for="caption" class="form-label">{{ __('messages.story_caption') }}</label>
            <textarea class="form-control" id="caption" name="caption">{{ old('caption') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="privacy">{{ __('messages.privacy') }}</label>
            <select name="privacy" id="privacy" class="form-control">
                <option value="public">{{ __('messages.public') }}</option>
                <option value="friends">{{ __('messages.friends') }}</option>
                <option value="private">{{ __('messages.private') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ __('messages.create_story') }}</button>
    </form>
@endsection
