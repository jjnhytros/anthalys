@extends('layouts.main')

@section('title', 'Exercise')

@section('content')
    <h1>{{ $lesson->title }}</h1>

    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $nextExercise->question }}</h5>

            <form action="{{ route('exercises.submit', ['lesson_id' => $lesson->id, 'exercise_id' => $nextExercise->id]) }}"
                method="POST">
                @csrf

                @if ($nextExercise->type === 'multiple_choice')
                    @foreach ($nextExercise->options as $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" value="{{ $option }}"
                                id="option_{{ $loop->index }}">
                            <label class="form-check-label" for="option_{{ $loop->index }}">
                                {{ $option }}
                            </label>
                        </div>
                    @endforeach
                @elseif($nextExercise->type === 'translation')
                    <div class="form-group">
                        <input type="text" name="answer" class="form-control" placeholder="Your translation">
                    </div>
                @endif

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>

            @if (session('status'))
                <div class="alert alert-info mt-3">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
@endsection
