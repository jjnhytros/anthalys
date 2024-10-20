@extends('layouts.main')

@section('title', 'Search Results')

@section('content')
    <h1>Search Results</h1>

    @if ($articles->isEmpty())
        <p>No articles found for your search.</p>
    @else
        <ul>
            @foreach ($articles as $article)
                <li><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></li>
            @endforeach
        </ul>
    @endif
@endsection
