@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.hashtag') }}: #{{ $hashtag }}</h1>

    @if ($posts->isEmpty())
        <p>{{ __('messages.no_posts_with_hashtag') }}</p>
    @else
        <ul class="list-group">
            @foreach ($posts as $post)
                <li class="list-group-item">
                    <strong>{{ $comment->character->first_name }}
                        @if ($comment->character->profile->verified)
                            <span class="badge bg-primary">{{ __('messages.verified') }}</span>
                        @endif
                    </strong>
                    <p>{!! $post->renderContentWithHashtagsAndMentions() !!}</p>
                    <small>{{ $post->created_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
