<h1>{{ __('messages.analytics_report') }}</h1>

<h3>{{ __('messages.posts') }}</h3>
@foreach ($posts as $post)
    <div class="post-analytics">
        <h5>{{ $post->title }}</h5>
        <p>{{ __('messages.views') }}: {{ $post->total_views }}</p>
        <p>{{ __('messages.likes') }}: {{ $post->reactions->count() }}</p>
        <p>{{ __('messages.comments') }}: {{ $post->comments->count() }}</p>
    </div>
@endforeach

<h3>{{ __('messages.videos') }}</h3>
@foreach ($videos as $video)
    <div class="video-analytics">
        <h5>{{ $video->description }}</h5>
        <p>{{ __('messages.views') }}: {{ $video->total_views }}</p>
        <p>{{ __('messages.likes') }}: {{ $video->reactions->count() }}</p>
        <p>{{ __('messages.comments') }}: {{ $video->comments->count() }}</p>
    </div>
@endforeach

<h3>{{ __('messages.stories') }}</h3>
@foreach ($stories as $story)
    <div class="story-analytics">
        <h5>{{ $story->title }}</h5>
        <p>{{ __('messages.views') }}: {{ $story->total_views }}</p>
        <p>{{ __('messages.likes') }}: {{ $story->reactions->count() }}</p>
        <p>{{ __('messages.comments') }}: {{ $story->comments->count() }}</p>
    </div>
@endforeach
