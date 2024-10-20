<form action="{{ route('feed.filter') }}" method="GET" class="mb-4">
    <div class="form-group">
        <label for="category">{{ __('messages.select_category') }}</label>
        <select name="category" id="category" class="form-control">
            <option value="">{{ __('messages.all_categories') }}</option>
            <option value="work">{{ __('messages.work') }}</option>
            <option value="entertainment">{{ __('messages.entertainment') }}</option>
            <option value="technology">{{ __('messages.technology') }}</option>
        </select>
    </div>

    <div class="form-group mt-3">
        <label for="sort_by">{{ __('messages.sort_by') }}</label>
        <select name="sort_by" id="sort_by" class="form-control">
            <option value="recent">{{ __('messages.recent') }}</option>
            <option value="popular">{{ __('messages.popular') }}</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary mt-3">{{ __('messages.apply_filters') }}</button>
</form>

<!-- Sezione per mostrare i post filtrati -->
@if ($posts->isEmpty())
    <p>{{ __('messages.no_posts') }}</p>
@else
    <ul class="list-group">
        @foreach ($posts as $post)
            <li class="list-group-item">
                <strong>{{ $post->character->first_name }} {{ $post->character->last_name }}
                    @if ($post->character->profile->verified)
                        <span class="badge bg-primary">{{ __('messages.verified') }}</span>
                    @endif
                </strong>
                <p>{{ $post->content }}</p>

                @if ($post->hasMedia())
                    @if (str_contains($post->media_path, ['.jpg', '.png', '.gif']))
                        <img src="{{ asset('storage/' . $post->media_path) }}" class="img-fluid" alt="Post Media">
                    @else
                        <video src="{{ asset('storage/' . $post->media_path) }}" controls class="img-fluid"></video>
                    @endif
                @endif

                <small>{{ $post->created_at->diffForHumans() }}</small>
            </li>
        @endforeach
    </ul>
@endif
