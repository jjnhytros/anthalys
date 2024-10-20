@extends('layouts.main')

@section('title', 'Edit Recommendation Weights')

@section('content')
    <div class="container">
        <h1>Edit Recommendation Weights</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.updateWeights') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="view">Weight for Views</label>
                <input type="number" name="view" class="form-control" id="view"
                    value="{{ old('view', $weights['view']) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="like">Weight for Likes</label>
                <input type="number" name="like" class="form-control" id="like"
                    value="{{ old('like', $weights['like']) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="comment">Weight for Comments</label>
                <input type="number" name="comment" class="form-control" id="comment"
                    value="{{ old('comment', $weights['comment']) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="time_spent">Weight for Time Spent (per second)</label>
                <input type="number" step="0.01" name="time_spent" class="form-control" id="time_spent"
                    value="{{ old('time_spent', $weights['time_spent']) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Weights</button>
        </form>
    </div>
@endsection
