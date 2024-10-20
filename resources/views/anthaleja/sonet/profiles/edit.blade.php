@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Modifica il tuo Profilo</h2>
        <form action="{{ route('profiles.update', $profile->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Altri campi del profilo -->

            <div class="mb-3">
                <label for="skills" class="form-label">Competenze (separate da virgole):</label>
                <input type="text" name="skills[]" class="form-control" placeholder="Es: PHP, JavaScript, Laravel"
                    value="{{ implode(', ', $profile->skills ?? []) }}">
            </div>

            <button type="submit" class="btn btn-primary">Aggiorna Profilo</button>
        </form>
    </div>
@endsection
