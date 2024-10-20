@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Recensioni per {{ $character->username }}</h2>
        @if ($character->reviews->isEmpty())
            <p>Non ci sono recensioni disponibili per questo personaggio.</p>
        @else
            @foreach ($character->reviews as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            Valutazione:
                            @php
                                $rating = $review->rating;
                                $fullStars = floor($rating / 2); // Stelle intere (ogni stella intera vale 2 punti)
                                $halfStar = $rating % 2 ? 1 : 0; // Se c'è una mezza stella
                                $emptyStars = 6 - $fullStars - $halfStar; // Stelle vuote
                            @endphp

                            <!-- Mostra icona speciale se punteggio è 12 -->
                            @if ($rating == 12)
                                {!! getIcon('stars', 'bi', ['', ['format' => ' (' . $review->rating . ')']]) !!}
                            @else
                                <!-- Stelle piene -->
                                @for ($i = 0; $i < $fullStars; $i++)
                                    {!! getIcon('star-fill', 'bi', ['', ['format' => ' (' . $review->rating . ')']]) !!}
                                @endfor

                                <!-- Mezza stella -->
                                @if ($halfStar)
                                    {!! getIcon('star-half', 'bi', ['', ['format' => ' (' . $review->rating . ')']]) !!}
                                @endif

                                <!-- Stelle vuote -->
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    {!! getIcon('star', 'bi', ['', ['format' => ' (' . $review->rating . ')']]) !!}
                                @endfor
                            @endif
                        </h5>
                        <p class="card-text">{{ $review->review }}</p>
                        <p class="card-text"><small class="text-muted">Recensito da: {{ $review->reviewer->username }}</small>
                        </p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
