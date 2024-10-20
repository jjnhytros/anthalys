@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.short_videos') }}</h1>

    <div class="short-videos-feed">
        @foreach ($shortVideos as $video)
            <div class="short-video">
                <video src="{{ asset('storage/' . $video->video_path) }}" controls class="w-100"></video>

                @if ($video->audio_path)
                    <audio src="{{ asset('storage/' . $video->audio_path) }}" controls class="mt-2"></audio>
                @endif

                <p>{{ $video->description }}</p>

                <!-- Aggiungi qui reazioni e commenti -->
            </div>
        @endforeach
    </div>
@endsection
