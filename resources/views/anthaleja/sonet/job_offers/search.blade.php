@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Cerca Offerte di Lavoro</h2>
        <form action="{{ route('job_offers.search') }}" method="GET" class="mt-3">
            <div class="mb-3">
                <label for="skills" class="form-label">Competenze (separate da virgole):</label>
                <input type="text" name="skills" class="form-control" placeholder="Es: PHP, JavaScript, Laravel">
            </div>

            <div class="mb-3">
                <label for="job_type" class="form-label">Tipologia di Lavoro:</label>
                <select name="job_type" class="form-select">
                    <option value="">Tutti</option>
                    <option value="freelance">Freelance</option>
                    <option value="full_time">Assunzione a lungo termine</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Luogo:</label>
                <input type="text" name="location" class="form-control" placeholder="Es: Milano, Roma">
            </div>

            <button type="submit" class="btn btn-primary">Cerca</button>
        </form>
    </div>
@endsection
