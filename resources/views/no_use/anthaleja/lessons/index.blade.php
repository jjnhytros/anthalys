@extends('layouts.main')

@section('title', 'Lessons')

@section('content')
    <h1>All Lessons</h1>
    <ul>
        @foreach ($lessons as $lesson)
            <li>
                <a href="{{ route('lessons.show', $lesson->id) }}">{{ $lesson->title }} ({{ $lesson->difficulty_level }})</a>
            </li>
        @endforeach
    </ul>
@endsection
