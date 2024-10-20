@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Offerte di Lavoro</h2>
        @if ($jobOffers->isEmpty())
            <p>Non ci sono offerte di lavoro disponibili al momento.</p>
        @else
            @foreach ($jobOffers as $offer)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $offer->title }}</h5>
                        <p class="card-text">{{ $offer->description }}</p>
                        <p class="card-text"><strong>Luogo:</strong> {{ $offer->location }}</p>
                        <p class="card-text"><strong>Salario:</strong> €{{ number_format($offer->salary, 2) }}</p>
                        <p class="card-text"><strong>Tipo di lavoro:</strong>
                            {{ $offer->job_type == 'freelance' ? 'Freelance' : 'Assunzione a lungo termine' }}</p>
                        <p class="card-text"><strong>Competenze Richieste:</strong>
                            {{ implode(', ', json_decode($offer->required_skills)) }}</p>
                        <p class="card-text"><strong>Negoziabile:</strong> {{ $offer->negotiable ? 'Sì' : 'No' }}</p>
                        <p class="card-text"><small class="text-muted">Pubblicato da:
                                {{ $offer->character->username }}</small></p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
