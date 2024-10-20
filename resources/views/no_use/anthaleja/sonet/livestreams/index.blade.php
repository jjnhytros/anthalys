@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.live_streams') }}</h1>

    @if ($liveStreams->isEmpty())
        <p>{{ __('messages.no_live_streams') }}</p>
    @else
        <div class="row">
            @foreach ($liveStreams as $liveStream)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>{{ $liveStream->character->first_name }}
                                {{ $liveStream->character->last_name }}
                                @if ($liveStream->character->profile->verified)
                                    <span class="badge bg-primary">{{ __('messages.verified') }}</span>
                                @endif
                            </strong>
                        </div>
                        <div class="card-body">
                            <h5>{{ $liveStream->title }}</h5>
                            <p>{{ __('messages.live_now') }}</p>
                            <a href="#" class="btn btn-primary">{{ __('messages.join_live') }}</a>

                            <!-- Reazioni -->
                            <form action="{{ route('reactions.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="live_stream_id" value="{{ $liveStream->id }}">
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
                        </div>

                        <!-- Box per commenti -->
                        <div id="comments">
                            <!-- I commenti verranno caricati qui -->
                        </div>
                        <form id="comment-form" method="POST" action="{{ route('comments.store') }}">
                            @csrf
                            <input type="hidden" name="live_stream_id" value="{{ $liveStream->id }}">
                            <textarea id="comment_input" name="content" class="form-control" rows="2"
                                placeholder="{{ __('messages.add_comment') }}"></textarea>
                            <button type="button" class="btn btn-primary mt-2"
                                onclick="sendComment()">{{ __('messages.submit_comment') }}</button>
                        </form>

                        <!-- Polling per aggiornare i commenti -->
                        <script>
                            function loadComments() {
                                $.ajax({
                                    url: '{{ route('livestreams.comments', $liveStream->id) }}',
                                    method: 'GET',
                                    success: function(data) {
                                        let commentBox = document.getElementById('comments');
                                        commentBox.innerHTML = '';
                                        data.forEach(function(comment) {
                                            commentBox.innerHTML += '<p><strong>' + comment.character.first_name +
                                                ':</strong> ' + comment.content + '</p>';
                                        });
                                    }
                                });
                            }

                            function sendComment() {
                                let formData = new FormData(document.getElementById('comment-form'));
                                $.ajax({
                                    url: '{{ route('comments.store') }}',
                                    method: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {
                                        document.getElementById('comment_input').value = '';
                                        loadComments(); // Ricarica i commenti
                                    }
                                });
                            }

                            // Esegui il polling ogni 5 secondi
                            setInterval(loadComments, 5000);
                        </script>
                    </div>
                </div>
        </div>
    @endforeach
    </div>
    @endif

@endsection
