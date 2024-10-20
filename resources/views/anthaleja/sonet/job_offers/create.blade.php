@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Pubblica un'Offerta di Lavoro</h2>
        <form action="{{ route('job_offers.store') }}" method="POST" class="mt-3">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Titolo del Lavoro:</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrizione:</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Luogo di Lavoro:</label>
                <input type="text" name="location" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="salary" class="form-label">Salario Offerto:</label>
                <input type="number" name="salary" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="job_type" class="form-label">Tipologia di Lavoro:</label>
                <select name="job_type" class="form-select" required>
                    <option value="freelance">Freelance</option>
                    <option value="full_time">Assunzione a lungo termine</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="required_skills" class="form-label">Competenze Richieste (separate da virgole):</label>
                <input type="text" name="required_skills[]" class="form-control"
                    placeholder="Es: PHP, JavaScript, Laravel" required>
            </div>

            <div class="mb-3">
                <label for="negotiable" class="form-label">È negoziabile?</label>
                <select name="negotiable" class="form-select" required>
                    <option value="0">No</option>
                    <option value="1">Sì</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Pubblica Offerta</button>
        </form>
    </div>
@endsection
