{{-- resources/views/anthaleja/sonet/partials/sonet.blade.php --}}
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">{{ $sonet->character->username }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ $sonet->created_at->format('d M Y, H:i') }}</h6>
        <p class="card-text">{{ $sonet->content }}</p>
        @include('components.reputation_form', ['character' => $sonet->character])

        @if ($sonet->media)
            <p><a href="{{ $sonet->media }}" target="_blank">Visualizza media</a></p>
        @endif

        <!-- Pulsante "Commenta" con icona -->
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#commentModal-{{ $sonet->id }}">
            <i class="bi bi-chat-dots"></i> Commenta
        </button>

        <!-- Commenti caricati -->
        <div id="comment-list-{{ $sonet->id }}">
            @include('anthaleja.sonet.partials.comment_list', ['comments' => $sonet->comments->take(24)])
        </div>

        <!-- Link per caricare più commenti -->
        @if ($sonet->comments->count() > 24)
            <a href="javascript:void(0)" class="btn btn-link" id="load-more-{{ $sonet->id }}"
                onclick="loadMoreComments({{ $sonet->id }}, 24)">Mostra altri commenti...</a>
        @endif

        <!-- Spinner di caricamento per i commenti -->
        <div id="loading-comments-{{ $sonet->id }}" class="text-center my-4" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Caricamento...</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal per aggiungere commenti -->
<div class="modal fade" id="commentModal-{{ $sonet->id }}" tabindex="-1"
    aria-labelledby="commentModalLabel-{{ $sonet->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel-{{ $sonet->id }}">Commenta il Sonet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Post:</strong> {{ $sonet->content }}</p>
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sonet_post_id" value="{{ $sonet->id }}">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="3" placeholder="Aggiungi un commento..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Invia commento</button>
                </form>
            </div>
        </div>
    </div>
</div>
