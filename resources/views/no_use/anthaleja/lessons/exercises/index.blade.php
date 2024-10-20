@extends('layouts.main')

@section('title', 'Exercises for ' . $lesson->title)

@section('content')
    <h1>Exercises for {{ $lesson->title }}</h1>

    @foreach ($exercises as $exercise)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $exercise->question }}</h5>

                @if ($exercise->type === 'multiple_choice')
                    <form
                        action="{{ route('exercises.submit', ['lesson_id' => $lesson->id, 'exercise_id' => $exercise->id]) }}"
                        method="POST">
                        @csrf
                        @if (is_array($exercise->options) && !empty($exercise->options))
                            @foreach ($exercise->options as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answer" value="{{ $option }}"
                                        id="option_{{ $loop->index }}">
                                    <label class="form-check-label" for="option_{{ $loop->index }}">
                                        {{ $option }}
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p>No options available for this exercise.</p>
                        @endif
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                @elseif($exercise->type === 'translation')
                    <form
                        action="{{ route('exercises.submit', ['lesson_id' => $lesson->id, 'exercise_id' => $exercise->id]) }}"
                        method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="answer" class="form-control" placeholder="Your translation">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
@endsection
