@extends('layouts.main')

@section('meta')
    <meta name="description"
        content="Wiki Anthalys - Esplora il mondo di Anthalys, articoli in evidenza, ultime novità e molto altro.">
    <title>Wiki Anthalys | Homepage</title>
@endsection

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Colonna Sinistra - Sidebar -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Categorie
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($categories as $category)
                            <li class="list-group-item">
                                {{-- <a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a> --}}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        Statistiche del Wiki
                    </div>
                    <div class="card-body">
                        <p>Articoli Totali: <strong>{{ $articleCount }}</strong></p>
                        <p>Contributori: <strong>{{ $contributorCount }}</strong></p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-secondary text-white">
                        Link Utili
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="#">Guida al Wiki</a></li>
                        <li class="list-group-item"><a href="#">Linee Guida</a></li>
                        <li class="list-group-item"><a href="#">Domande Frequenti</a></li>
                        <li class="list-group-item"><a href="#">Contatta il Team</a></li>
                    </ul>
                </div>
            </div>

            <!-- Colonna Centrale - Contenuto Principale -->
            <div class="col-lg-6 col-md-8 mb-4">
                <div class="search-bar text-center mb-4">
                    <h1 class="display-5">Benvenuto su Anthalys Wiki</h1>
                    <form action="{{ route('articles.search') }}" method="GET" class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cerca nel wiki..." name="query"
                                value="{{ request()->input('query') }}">
                            <button type="submit" class="btn btn-primary">Cerca</button>
                        </div>
                    </form>
                </div>

                <h4 class="my-4">Articoli in Evidenza</h4>
                <div class="list-group">
                    @foreach ($featuredArticles as $article)
                        <a href="{{ route('articles.show', $article->slug) }}"
                            class="list-group-item list-group-item-action">
                            {{ $article->title }}
                        </a>
                    @endforeach
                </div>

                <div class="mt-4">
                    <h4>Ultimi Articoli</h4>
                    <ul class="list-unstyled">
                        @foreach ($latestArticles as $article)
                            <li><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Colonna Destra - Sezione Voci in Vetrina -->
            <div class="col-lg-3 col-md-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        Voci in Vetrina
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach ($featuredArticles as $article)
                                <li><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-warning text-white">
                        Portali Tematici
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="#">Storia</a></li>
                        <li class="list-group-item"><a href="#">Cultura</a></li>
                        <li class="list-group-item"><a href="#">Geografia</a></li>
                        <li class="list-group-item"><a href="#">Economia</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
