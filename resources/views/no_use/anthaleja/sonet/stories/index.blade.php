@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.stories_feed') }}</h1>

    @if ($stories->isEmpty())
        <p>{{ __('messages.no_stories') }}</p>
    @else
        <div class="row">
            @foreach ($stories as $story)
                <div class="col-md-3">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>{{ $story->character->first_name }} {{ $story->character->last_name }}
                                @if ($story->character->profile->verified)
                                    <span class="badge bg-primary">{{ __('messages.verified') }}</span>
                                @endif
                            </strong>
                        </div>
                        <div class="card-body">
                            @if (preg_match('/(jpeg|png|jpg|gif)$/i', $story->media))
                                <img src="{{ asset('storage/' . $story->media) }}" class="img-fluid" alt="Story Media">
                            @else
                                <video src="{{ asset('storage/' . $story->media) }}" controls class="img-fluid"></video>
                            @endif
                            <p>{{ $story->caption }}</p>

                            <!-- Reazioni -->
                            <form action="{{ route('reactions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="story_id" value="{{ $story->id }}">
                                <button type="submit" name="type" value="like"
                                    class="btn btn-outline-primary">👍</button>
                                <button type="submit" name="type" value="love"
                                    class="btn btn-outline-danger">❤️</button>
                                <button type="submit" name="type" value="haha"
                                    class="btn btn-outline-warning">😂</button>
                                <button type="submit" name="type" value="wow"
                                    class="btn btn-outline-info">😮</button>
                                <button type="submit" name="type" value="sad"
                                    class="btn btn-outline-secondary">😢</button>
                                <button type="submit" name="type" value="angry"
                                    class="btn btn-outline-dark">😡</button>
                            </form>

                            <!-- Commenti -->
                            <form id="comment-form" method="POST" action="{{ route('comments.store') }}">
                                @csrf
                                <input type="hidden" name="story_id" value="{{ $story->id }}">
                                <textarea id="comment_input" name="content" class="form-control" rows="2"
                                    placeholder="{{ __('messages.add_comment') }}"></textarea>
                                <button type="button" class="btn btn-primary mt-2"
                                    onclick="sendComment()">{{ __('messages.submit_comment') }}</button>
                            </form>

                            <!-- Elenco Commenti -->
                            <div class="mt-4">
                                <h5>{{ __('messages.comments') }}</h5>
                                @foreach ($story->comments as $comment)
                                    <div class="mb-2">
                                        <strong>{{ $comment->character->first_name }}
                                            {{ $comment->character->last_name }}</strong>
                                        <p>{{ $comment->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
