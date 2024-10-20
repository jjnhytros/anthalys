{{-- resources/views/anthaleja/sonet/partials/sonet_list.blade.php --}}
@forelse ($sonets as $sonet)
    <div class="card mb-2 shadow-sm border-0">
        <div class="card-body p-2">
            <div class="d-flex justify-content-between">
                <h5 class="h6 card-title mb-1">{{ $sonet['character']['username'] }}</h5>
                <small class="text-muted">{{ \Carbon\Carbon::parse($sonet['created_at'])->format('d M, H:i') }}</small>
            </div>
            <p class="card-text mb-1 small">{{ $sonet['content'] }}</p>

            <!-- Sezione per la visualizzazione dei media -->
            @if (!empty($sonet['media']))
                @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $sonet['media']))
                    <img src="{{ asset('storage/' . $sonet['media']) }}" alt="media" class="img-fluid mt-2">
                @elseif (preg_match('/\.(mp4|mov|ogg|webm)$/i', $sonet['media']))
                    <video controls class="img-fluid mt-2">
                        <source src="{{ asset('storage/' . $sonet['media']) }}" type="video/mp4">
                    </video>
                @else
                    <a href="{{ asset('storage/' . $sonet['media']) }}" target="_blank">{{ $sonet['media'] }}</a>
                @endif
            @endif

            <!-- Pulsante Lumina -->
            <div class="d-flex justify-content-between mt-2">
                <button class="btn btn-sm btn-light"
                    onclick="toggleLumina({{ $sonet['id'] }}, 'App\\Models\\SonetPost')">
                    {!! getIcon('star', 'bi') !!} Lumina <span
                        id="lumina-count-{{ $sonet['id'] }}">{{ $sonet['lumina_count'] ?? 0 }}</span>
                </button>
                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#actionModal"
                    data-action="comment" data-sonet-id="{{ $sonet['id'] }}"
                    data-author="{{ $sonet['character']['username'] }}" data-sonet-content="{{ $sonet['content'] }}">
                    {!! getIcon('chat-dots', 'bi', 'Commenta') !!}
                </button>
                <button class="btn btn-sm btn-light">Share</button>
            </div>
            @include('components.reputation_form', ['character' => $sonet->character])

            <!-- Sezione Commenti -->
            <div class="card-footer bg-white p-2">
                <div id="comment-list-{{ $sonet['id'] }}">
                    @if (isset($sonet['comments']) && $sonet['comments']->isNotEmpty())
                        @foreach ($sonet['comments'] as $comment)
                            <div class="comment mb-1">
                                <strong>{{ $comment['character']['username'] }}:</strong>
                                <span class="small">{{ $comment['content'] }}</span>
                                <button type="button" class="btn btn-sm btn-secondary m-2 p-1" data-bs-toggle="modal"
                                    data-bs-target="#actionModal" data-action="reply"
                                    data-sonet-id="{{ $sonet['id'] }}" data-comment-id="{{ $comment['id'] }}"
                                    data-author="{{ $comment['character']['username'] }}"
                                    data-comment-content="{{ $comment['content'] }}">
                                    {!! getIcon('reply', 'bi', 'Rispondi') !!}
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <p>Non ci sono altri post da visualizzare.</p>
@endforelse
