@extends('layouts.main')

@section('title', 'Lesson Completed')

@section('content')
    <h1>Lesson Completed</h1>
    <p>Congratulations! You have successfully completed the lesson.</p>

    <a href="{{ route('lessons.index') }}" class="btn btn-primary">Back to Lessons</a>
@endsection
