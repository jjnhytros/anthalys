@extends('layouts.main')

@section('content')
    <h1>{{ $liveStream->title }}</h1>
    <p><strong>{{ __('messages.live_started') }}:</strong> {{ $liveStream->started_at }}</p>
    <p><strong>{{ __('messages.views') }}:</strong> {{ $liveStream->views_count }}</p>
    <p><strong>{{ __('messages.reactions') }}:</strong> {{ $liveStream->reactions_count }}</p>
    <p><strong>{{ __('messages.comments') }}:</strong> {{ $liveStream->comments_count }}</p>

    <!-- Visualizzazione della diretta (ad esempio, video player) -->

    <!-- Commenti e Reazioni -->
    @include('anthaleja.sonet.comments.form', ['liveStream' => $liveStream])
@endsection
