@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.upload_short_video') }}</h1>

    <form action="{{ route('shortvideos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="video" class="form-label">{{ __('messages.upload_video') }}</label>
            <input type="file" class="form-control" id="video" name="video" required>
        </div>

        <div class="mb-3">
            <label for="audio" class="form-label">{{ __('messages.upload_audio') }}</label>
            <input type="file" class="form-control" id="audio" name="audio">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">{{ __('messages.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="privacy">{{ __('messages.privacy') }}</label>
            <select name="privacy" id="privacy" class="form-control">
                <option value="public">{{ __('messages.public') }}</option>
                <option value="friends">{{ __('messages.friends') }}</option>
                <option value="private">{{ __('messages.private') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.upload') }}</button>
    </form>
@endsection
