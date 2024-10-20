{{-- resources/views/anthaleja/sonet/partials/comment_list.blade.php --}}
@foreach ($comments as $comment)
    <div class="mb-2" style="margin-left: {{ $comment->parent_id ? '20px' : '0px' }};">
        <strong>{{ $comment->character->username }}</strong>
        <p>{{ $comment->content }}</p>
        <small>{{ $comment->created_at->format('d M Y, H:i') }}</small>
        @include('components.reputation_form', ['character' => $comment->character])

        <!-- Pulsante "Commenta" per rispondere al commento -->
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showReplyForm({{ $comment->id }})">
            <i class="bi bi-reply"></i> Commenta
        </button>

        <!-- Form di risposta nascosto inizialmente -->
        <div id="reply-form-{{ $comment->id }}" style="display: none; margin-top: 10px;">
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sonet_post_id" value="{{ $sonet->id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id ?? '' }}">
                <!-- Se è un commento a un altro commento -->
                <div class="mb-3">
                    <textarea name="content" class="form-control" rows="3" placeholder="Aggiungi un commento..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Invia commento</button>
            </form>
        </div>

        <!-- Se il commento ha figli, mostrali sotto di esso -->
        @if ($comment->children && $comment->children->count() > 0)
            <div class="children-comments" style="margin-left: 20px;">
                @include('anthaleja.sonet.partials.comment_list', ['comments' => $comment->children])
            </div>
        @endif
    </div>
@endforeach

<script>
    function showReplyForm(commentId) {
        var replyForm = document.getElementById('reply-form-' + commentId);
        if (replyForm.style.display === 'none' || replyForm.style.display === '') {
            replyForm.style.display = 'block'; // Mostra il form di risposta
        } else {
            replyForm.style.display = 'none'; // Nascondi il form di risposta
        }
    }
</script>
