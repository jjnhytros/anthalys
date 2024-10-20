@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Offerte di Lavoro Raccomandate per le tue Competenze</h2>
        @if ($recommendedJobs->isEmpty())
            <p>Non ci sono offerte di lavoro corrispondenti alle tue competenze al momento.</p>
        @else
            @foreach ($recommendedJobs as $offer)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $offer->title }}</h5>
                        <p class="card-text">{{ $offer->description }}</p>
                        <p class="card-text"><strong>Luogo:</strong> {{ $offer->location }}</p>
                        <p class="card-text"><strong>Salario:</strong> €{{ number_format($offer->salary, 2) }}</p>
                        <p class="card-text"><strong>Competenze Richieste:</strong>
                            {{ implode(', ', json_decode($offer->required_skills)) }}</p>
                        <p class="card-text"><small class="text-muted">Pubblicato da:
                                {{ $offer->character->username }}</small></p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
