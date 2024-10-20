{{-- resources/views/anthaleja/sonet/posts/timeline.blade.php --}}
@extends('anthaleja.sonet.layouts.main')

@section('content')
    {{-- resources/views/anthaleja/sonet/posts/timeline.blade.php --}}
    <div class="container-fluid p-3">
        <div class="row">
            <div class="col-md-3 sidebar-left"></div>
            <div class="col-md-6 main-content">
                <div class="d-flex justify-content-between mb-2">
                    <h2>Timeline</h2>
                    <!-- Pulsante per aprire il modal per creare un Sonet -->
                    <button type="button" class="btn btn-sm btn-primary m-2 p-1" data-bs-toggle="modal"
                        data-bs-target="#actionModal" data-action="post">
                        {!! getIcon('plus-circle', 'bi', 'Crea un Sonet') !!}
                    </button>
                </div>

                <div id="sonet-list">
                    <!-- Qui vengono caricati dinamicamente i sonet -->
                    @forelse ($sonets as $sonet)
                        <div class="card mb-2 shadow-sm border-0">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between">
                                    <h5 class="h6 card-title mb-1">{{ $sonet['character']['username'] }}</h5>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($sonet['created_at'])->format('d M, H:i') }}
                                    </small>
                                </div>
                                <p class="card-text mb-1 small">{{ $sonet['content'] }}</p>

                                @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $sonet['media']))
                                    <img src="{{ asset($sonet['media']) }}" alt="media" class="img-fluid">
                                @elseif (preg_match('/\.(mp4|mov|ogg|webm)$/i', $sonet['media']))
                                    <video controls>
                                        <source src="{{ asset($sonet['media']) }}" type="video/mp4">
                                    </video>
                                @elseif (preg_match(
                                        '/\.(pdf|doc|docx|xls|xlsx|ppt|pptx|txt|csv|rtf|odt|ods|odp|epub|mobi|html|xml|json|zip|rar)$/i',
                                        $sonet['media']))
                                    <!-- Anteprima del documento -->
                                    <a href="{{ asset($sonet['media']) }}"
                                        target="_blank">{{ basename($sonet['media']) }}</a>
                                @else
                                    <a href="{{ $sonet['media'] }}" target="_blank">{{ $sonet['media'] }}</a>
                                @endif
                                @include('components.reputation_form', ['character' => $sonet->character])

                                <!-- Action buttons (like, comment, share) -->
                                <div class="d-flex justify-content-between mt-2">
                                    <button class="btn btn-sm btn-light"
                                        onclick="toggleLumina({{ $sonet['id'] }}, 'App\\Models\\SonetPost')">
                                        {!! getIcon('star', 'bi') !!} Lumina <span
                                            id="lumina-count-{{ $sonet['id'] }}">{{ $sonet['lumina_count'] ?? 0 }}</span>
                                    </button>
                                    <button class="btn btn-sm btn-light" data-bs-toggle="modal"
                                        data-bs-target="#actionModal" data-action="comment"
                                        data-sonet-id="{{ $sonet['id'] }}"
                                        data-author="{{ $sonet['character']['username'] }}"
                                        data-sonet-content="{{ $sonet['content'] }}">
                                        {!! getIcon('chat-dots', 'bi', 'Commenta') !!}
                                    </button>
                                    <button class="btn btn-sm btn-light">Share</button>
                                </div>

                                <!-- Pulsante per visualizzare o aggiungere commenti -->
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-sm btn-primary m-2 p-1" data-bs-toggle="modal"
                                        data-bs-target="#actionModal" data-action="comment"
                                        data-sonet-id="{{ $sonet['id'] }}"
                                        data-author="{{ $sonet['character']['username'] }}"
                                        data-sonet-content="{{ $sonet['content'] }}">
                                        {!! getIcon('chat-dots', 'bi', 'Commenta') !!}
                                    </button>
                                </div>
                            </div>

                            <!-- Sezione Commenti -->
                            <div class="card-footer bg-white p-2">
                                <div id="comment-list-{{ $sonet['id'] }}">
                                    @if (isset($sonet['comments']) && $sonet['comments']->isNotEmpty())
                                        @foreach ($sonet['comments'] as $comment)
                                            <div class="comment mb-1">
                                                <strong>{{ $comment['character']['username'] }}:</strong>
                                                <span class="small">{{ $comment['content'] }}</span>

                                                <!-- Pulsante Lumina per il commento -->
                                                <button class="btn btn-sm btn-light"
                                                    onclick="toggleLumina({{ $comment['id'] }}, 'App\\Models\\SonetComment')">
                                                    {!! getIcon('star', 'bi') !!} Lumina <span
                                                        id="lumina-count-comment-{{ $comment['id'] }}">{{ $comment['lumina_count'] ?? 0 }}</span>
                                                </button>

                                                <!-- Visualizza eventuali risposte al commento -->
                                                @if (!empty($comment['replies']))
                                                    <div class="ms-4">
                                                        @foreach ($comment['replies'] as $reply)
                                                            <div class="comment ps-3 mt-2">
                                                                <strong>{{ $reply['character']['username'] }}</strong>
                                                                <p>{{ $reply['content'] }}</p>
                                                                <small
                                                                    class="text-muted">{{ \Carbon\Carbon::parse($reply['created_at'])->format('d M Y, H:i') }}</small>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Pulsante per aggiungere una risposta -->
                                                <button type="button" class="btn btn-sm btn-secondary m-2 p-1"
                                                    data-bs-toggle="modal" data-bs-target="#actionModal" data-action="reply"
                                                    data-sonet-id="{{ $sonet['id'] }}"
                                                    data-comment-id="{{ $comment['id'] }}"
                                                    data-author="{{ $comment['character']['username'] }}"
                                                    data-comment-content="{{ $comment['content'] }}">
                                                    {!! getIcon('reply', 'bi', 'Rispondi') !!}
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Caricamento dinamico di più commenti -->
                                @if (isset($sonet['comments']) && $sonet['comments']->isNotEmpty() && count($sonet['comments']) > 24)
                                    <a href="javascript:void(0)" id="load-more-{{ $sonet['id'] }}"
                                        class="btn btn-link small" onclick="loadMoreComments({{ $sonet['id'] }}, 24)">
                                        {!! getIcon('chevron-down', 'bi', 'Mostra altri commenti') !!}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p>Non ci sono post da visualizzare.</p>
                    @endforelse
                </div>

                <!-- Spinner di caricamento per i post -->
                <div id="loading-posts" class="text-center my-4" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Caricamento...</span>
                    </div>
                </div>

                <!-- Pulsante per caricare più post -->
                @if (count($sonets) === 24)
                    <a href="javascript:void(0)" id="load-more-posts" class="btn btn-link" onclick="loadMorePosts(2)">
                        Mostra altri post...
                    </a>
                @endif
            </div>

            <div class="col-md-3 sidebar_right">
                @if (isset($ads))
                    <h4>Annunci sponsorizzati</h4>
                    @foreach ($ads as $ad)
                        <div class="ad">
                            <p>{{ $ad->content }}</p>
                            @if ($ad->type == 'ppv' || $ad->type == 'ppc')
                                <p>{{ $ad->type == 'ppv' ? 'Visualizzazioni' : 'Click' }}:
                                    {{ $ad->type == 'ppv' ? $ad->views : $ad->clicks }}</p>
                            @endif
                            <button
                                onclick="registerInteraction('{{ $ad->id }}', '{{ $ad->type == 'ppv' ? 'view' : 'click' }}')">
                                Interagisci
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Modal per la creazione/modifica dei Sonet, commenti o risposte -->
        @include('anthaleja.sonet.partials.timeline_modals')

        <script>
            // Script per gestire il caricamento dinamico e le interazioni
        </script>
    @endsection
