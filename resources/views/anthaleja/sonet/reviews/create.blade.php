@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Lascia una recensione</h2>
        <form action="{{ route('reviews.store', $character->id) }}" method="POST" class="mt-3">
            @csrf
            <div class="mb-3">
                <label for="rating" class="form-label">Valutazione (da 1 a 5):</label>
                <select name="rating" class="form-select" required>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="mb-3">
                <textarea name="review" class="form-control" rows="3" placeholder="Scrivi una recensione..." required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Invia recensione</button>
        </form>
    </div>
@endsection
