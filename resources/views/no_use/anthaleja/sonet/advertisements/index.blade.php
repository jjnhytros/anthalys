@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.available_ad_spaces') }}</h1>

    <div class="row">
        @foreach ($advertisements as $advertisement)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $advertisement->title }}</h5>
                        <p class="card-text">{{ $advertisement->description }}</p>
                        <p><strong>{{ __('messages.price') }}:</strong> ${{ $advertisement->price }}</p>

                        <!-- Reazioni -->
                        <div class="reactions">
                            <form action="{{ route('reaction.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="advertisement_id" value="{{ $advertisement->id }}">
                                <select name="type">
                                    <option value="like">👍 Like</option>
                                    <option value="love">❤️ Love</option>
                                    <option value="haha">😂 Haha</option>
                                    <option value="wow">😮 Wow</option>
                                    <option value="sad">😢 Sad</option>
                                    <option value="angry">😡 Angry</option>
                                </select>
                                <button type="submit" class="btn btn-primary">{{ __('messages.react') }}</button>
                            </form>
                        </div>

                        <!-- Commenti -->
                        <div class="comments mt-3">
                            <h5>{{ __('messages.comments') }}</h5>
                            @foreach ($advertisement->comments as $comment)
                                <div class="comment mb-2">
                                    <strong>{{ $comment->character->first_name }}
                                        @if ($comment->character->profile->verified)
                                            <span class="badge bg-primary">{{ __('messages.verified') }}</span>
                                        @endif
                                    </strong>
                                    <p>{{ $comment->content }}</p>
                                </div>
                            @endforeach

                            <form action="{{ route('comment.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="advertisement_id" value="{{ $advertisement->id }}">
                                <textarea name="content" rows="2" class="form-control" placeholder="{{ __('messages.add_comment') }}"></textarea>
                                <button type="submit"
                                    class="btn btn-primary mt-2">{{ __('messages.submit_comment') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
