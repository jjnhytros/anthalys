<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">{{ $content['title'] ?? 'Titolo non disponibile' }}</h3>
    </div>
    <div class="card-body">
        @if (isset($content) && is_array($content) && count($content) > 0)
            <ul class="list-group list-group-flush">
                @foreach ($content as $key => $value)
                    <!-- Non visualizzare la chiave 'title' come attributo -->
                    @if ($key !== 'title')
                        <li class="list-group-item">
                            <strong>{{ $key }}:</strong> {{ $value }}
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            <p class="text-muted">Nessun attributo disponibile.</p>
        @endif
    </div>
</div>
