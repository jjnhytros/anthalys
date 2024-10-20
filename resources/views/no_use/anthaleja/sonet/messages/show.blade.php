@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.conversation_with') }}</h1>

    <div class="messages">
        @foreach ($messages as $message)
            <div class="message">
                <strong>{{ $message->sender->first_name }}:</strong>
                <p>{{ $message->content }}</p>

                @if ($message->media_path)
                    @if ($message->media_type === 'image')
                        <img src="{{ asset('storage/' . $message->media_path) }}" class="img-fluid" alt="Image">
                    @elseif($message->media_type === 'video')
                        <video src="{{ asset('storage/' . $message->media_path) }}" controls class="img-fluid"></video>
                    @elseif($message->media_type === 'audio')
                        <audio controls>
                            <source src="{{ asset('storage/' . $message->media_path) }}"
                                type="audio/{{ $message->media_type }}">
                            Your browser does not support the audio element.
                        </audio>
                    @endif
                @endif
                <small>{{ $message->created_at->diffForHumans() }}</small>
            </div>
        @endforeach
    </div>

    <form action="{{ route('messages.store', $conversation->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="content" class="form-label">{{ __('messages.message') }}</label>
            <textarea name="content" id="content" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="media" class="form-label">{{ __('messages.upload_media') }}</label>
            <input type="file" name="media" id="media" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.send_message') }}</button>
    </form>
@endsection
