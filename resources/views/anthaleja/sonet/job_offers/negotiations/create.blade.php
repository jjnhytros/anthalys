@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Fai un'Offerta di Negoziato</h2>
        <form action="{{ route('negotiations.store', $jobOffer->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="salary_offered" class="form-label">Salario Offerto:</label>
                <input type="number" name="salary_offered" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Messaggio (opzionale):</label>
                <textarea name="message" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Invia Offerta</button>
        </form>
    </div>
@endsection
