@extends('layouts.main')

@section('title', $article->title)

@section('content')
    <h1>{{ $article->title }}</h1>
    @if (Auth::check())
        <form action="{{ route('articles.like', $article->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Like this article</button>
        </form>
    @endif


    <p>{!! $content !!}</p> <!-- Visualizza il contenuto tradotto -->

    @if ($infobox)
        <div class="infobox">
            {!! $infobox !!}
        </div>
    @endif

    <!-- Visualizza articoli correlati -->
    @if ($relatedArticles->isNotEmpty())
        <h3>Articoli Correlati</h3>
        <ul>
            @foreach ($relatedArticles as $relatedArticle)
                <li><a href="{{ route('articles.show', $relatedArticle->slug) }}">{{ $relatedArticle->title }}</a></li>
            @endforeach
        </ul>
    @endif

    <!-- Se ci sono articoli con titoli simili -->
    @if ($similarTitles->isNotEmpty())
        <h3>Articoli con titoli simili</h3>
        <ul>
            @foreach ($similarTitles as $similarArticle)
                <li><a href="{{ route('articles.show', $similarArticle->slug) }}">{{ $similarArticle->title }}</a></li>
            @endforeach
        </ul>
    @endif

    <!-- Se ci sono articoli con contenuti simili -->
    @if ($similarContent->isNotEmpty())
        <h3>Articoli con contenuti simili</h3>
        <ul>
            @foreach ($similarContent as $similarArticle)
                <li><a href="{{ route('articles.show', $similarArticle->slug) }}">{{ $similarArticle->title }}</a></li>
            @endforeach
        </ul>
    @endif

    <!-- Se ci sono articoli nella stessa categoria -->
    @if ($similarCategories->isNotEmpty())
        <h3>Articoli nella stessa categoria</h3>
        <ul>
            @foreach ($similarCategories as $similarArticle)
                <li><a href="{{ route('articles.show', $similarArticle->slug) }}">{{ $similarArticle->title }}</a></li>
            @endforeach
        </ul>
    @endif

    <!-- Se ci sono articoli duplicati o simili, suggerisci la fusione -->
    @if ($suggestMerge)
        <h3>Articoli duplicati o simili trovati</h3>
        <ul>
            @foreach ($similarTitles as $similarArticle)
                <li><a href="{{ route('articles.show', $similarArticle->slug) }}">{{ $similarArticle->title }}</a></li>
            @endforeach
            @foreach ($similarContent as $similarArticle)
                <li><a href="{{ route('articles.show', $similarArticle->slug) }}">{{ $similarArticle->title }}</a></li>
            @endforeach
        </ul>
        <a href="{{ route('articles.mergeForm', [$article->id, $similarTitles->first()->id]) }}"
            class="btn btn-warning">Fondi con articolo simile</a>
    @endif

    <script>
        let startTime = Date.now();

        window.addEventListener("beforeunload", function() {
            let endTime = Date.now();
            let timeSpent = (endTime - startTime) / 1000; // Tempo in secondi

            // Invia il tempo di permanenza al server
            navigator.sendBeacon("{{ route('articles.timeSpent', $article->id) }}", JSON.stringify({
                time_spent: timeSpent,
                _token: "{{ csrf_token() }}"
            }));
        });
    </script>

@endsection
