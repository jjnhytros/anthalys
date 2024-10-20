@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Gestisci le Offerte di Negoziato</h2>
        @foreach ($negotiations as $negotiation)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Offerta di Negoziato da {{ $negotiation->character->username }}</h5>
                    <p class="card-text">Salario Offerto: €{{ number_format($negotiation->salary_offered, 2) }}</p>
                    <p class="card-text">Messaggio: {{ $negotiation->message }}</p>
                    <form action="{{ route('negotiations.respond', $negotiation->id) }}" method="POST">
                        @csrf
                        <select name="status" class="form-select mb-3">
                            <option value="accepted">Accetta</option>
                            <option value="rejected">Rifiuta</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Invia Risposta</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
