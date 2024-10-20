@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.create_post') }}</h1>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="content" class="form-label">{{ __('messages.post_content') }}</label>
            <textarea class="form-control" id="content" name="content" rows="5">{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="media" class="form-label">{{ __('messages.upload_media') }}</label>
            <input type="file" class="form-control" id="media" name="media">
        </div>

        <div class="mb-3">
            <label for="privacy">{{ __('messages.privacy') }}</label>
            <select name="privacy" id="privacy" class="form-control">
                <option value="public">{{ __('messages.public') }}</option>
                <option value="friends">{{ __('messages.friends') }}</option>
                <option value="private">{{ __('messages.private') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ __('messages.create_post') }}</button>
    </form>
@endsection
