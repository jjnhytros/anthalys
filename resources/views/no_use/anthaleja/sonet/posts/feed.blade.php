@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.feed') }}</h1>

    @foreach ($posts as $post)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $comment->character->first_name }}
                    @if ($comment->character->profile->verified)
                        <span class="badge bg-primary">{{ __('messages.verified') }}</span>
                    @endif
                </strong>
            </div>
            <div class="card-body">
                <p>{{ $post->content }}</p>
                @if ($post->hasMedia())
                    @if (str_contains($post->media_path, ['.jpg', '.png', '.gif']))
                        <img src="{{ asset('storage/' . $post->media) }}" class="img-fluid" alt="Post Image">
                    @else
                        <video src="{{ asset('storage/' . $post->media) }}" controls class="img-fluid"></video>
                    @endif
                @endif
                <p><strong>Hashtags:</strong> {{ implode(', ', $post->getHashtags()) }}</p>
                <p><strong>Menzioni:</strong> {{ implode(', ', $post->getMentions()) }}</p>

                <!-- Reazioni -->
                <form action="{{ route('reactions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <button type="submit" name="type" value="like" class="btn btn-outline-primary">👍</button>
                    <button type="submit" name="type" value="love" class="btn btn-outline-danger">❤️</button>
                    <button type="submit" name="type" value="haha" class="btn btn-outline-warning">😂</button>
                    <button type="submit" name="type" value="wow" class="btn btn-outline-info">😮</button>
                    <button type="submit" name="type" value="sad" class="btn btn-outline-secondary">😢</button>
                    <button type="submit" name="type" value="angry" class="btn btn-outline-dark">😡</button>
                </form>
                <!-- Commenti -->
                <form id="comment-form" method="POST" action="{{ route('comments.store') }}">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <textarea id="comment_input" name="content" class="form-control" rows="2"
                        placeholder="{{ __('messages.add_comment') }}"></textarea>
                    <button type="button" class="btn btn-primary mt-2"
                        onclick="sendComment()">{{ __('messages.submit_comment') }}</button>
                </form>
                <!-- Elenco Commenti -->
                <div class="mt-4">
                    <h5>{{ __('messages.comments') }}</h5>
                    @foreach ($post->comments as $comment)
                        <div class="mb-2">
                            <strong>{{ $comment->character->first_name }} {{ $comment->character->last_name }}</strong>
                            <p>{{ $comment->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection
