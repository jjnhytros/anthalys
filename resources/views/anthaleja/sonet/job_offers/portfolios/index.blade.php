@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Il tuo Portafoglio</h2>
        @if ($portfolios->isEmpty())
            <p>Non ci sono progetti nel tuo portafoglio.</p>
        @else
            @foreach ($portfolios as $portfolio)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $portfolio->title }}</h5>
                        <p class="card-text">{{ $portfolio->description }}</p>
                        @if ($portfolio->link)
                            <p class="card-text"><a href="{{ $portfolio->link }}" class="btn btn-link">Visualizza Progetto</a>
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
