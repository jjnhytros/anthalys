@extends('layouts.main')

@section('title', 'Articles')

@section('content')
    <h1>Articles</h1>

    <ul class="list-group">
        @foreach ($articles as $article)
            <li class="list-group-item">
                <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
            </li>
        @endforeach
    </ul>

    {{ $articles->links() }}
@endsection
