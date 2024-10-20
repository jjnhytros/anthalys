@extends('layouts.main')

@section('content')
    <h1>{{ $character->first_name }} {{ $character->last_name }}</h1>
    @if ($character->profile->verified)
        <span class="badge bg-primary">{{ __('messages.verified') }}</span>
    @endif
    <div class="row">
        <div class="col-md-6">
            <h3>{{ __('messages.character_info') }}</h3>
            <p><strong>{{ __('messages.username') }}:</strong> {{ $character->username }}</p>
            <p><strong>{{ __('messages.bio') }}:</strong> {{ $character->bio }}</p>
            <p><strong>{{ __('messages.followers') }}:</strong> {{ $character->followers->count() }}</p>
            <p><strong>{{ __('messages.following') }}:</strong> {{ $character->following->count() }}</p>
        </div>

        <div class="col-md-6">
            <h3>{{ __('messages.actions') }}</h3>

            <!-- Pulsante Follow/Unfollow -->
            @if (Auth::user()->character->following->contains('character_id', $character->id))
                <form action="{{ route('characters.unfollow', $character->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">{{ __('messages.unfollow') }}</button>
                </form>
            @else
                <form action="{{ route('characters.follow', $character->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">{{ __('messages.follow') }}</button>
                </form>
            @endif

            <!-- Pulsante per Amicizia -->
            @if (Auth::user()->character->following->contains('character_id', $character->id) &&
                    Auth::user()->character->following->where('character_id', $character->id)->first()->is_friend)
                <form action="{{ route('characters.unfriend', $character->id) }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-warning">{{ __('messages.unfriend') }}</button>
                </form>
            @else
                @if (Auth::user()->character->following->contains('character_id', $character->id))
                    <form action="{{ route('characters.friend', $character->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success">{{ __('messages.add_friend') }}</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <!-- Mostra i post del personaggio -->
    <div class="mt-5">
        <h3>{{ __('messages.posts_by', ['character' => $character->first_name]) }}</h3>
        @if ($character->posts->isEmpty())
            <p>{{ __('messages.no_posts') }}</p>
        @else
            <ul class="list-group">
                @foreach ($character->posts as $post)
                    <li class="list-group-item">
                        <strong>{{ $post->created_at->format('d M Y') }}</strong>
                        <p>{{ $post->content }}</p>
                        @if ($post->media)
                            <img src="{{ asset('storage/' . $post->media) }}" class="img-fluid" alt="Post Media">
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
