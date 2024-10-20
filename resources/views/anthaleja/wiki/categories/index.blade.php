@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Categorie: {{ $category->name }}</h1>

        <!-- Form per l'ordinamento -->
        <form method="GET" action="{{ route('categories.show', $category->slug) }}">
            <select name="sort_by" class="form-control mb-3">
                <option value="created_at_desc">Data (recenti)</option>
                <option value="created_at_asc">Data (meno recenti)</option>
                <option value="title_asc">Titolo (A-Z)</option>
                <option value="title_desc">Titolo (Z-A)</option>
            </select>
            <button type="submit" class="btn btn-primary mb-4">Ordina</button>
        </form>

        <!-- Sottocategorie -->
        @if ($category->children->count() > 0)
            <h2>Sottocategorie</h2>
            <ul>
                @foreach ($category->children as $child)
                    <li>
                        <a href="{{ route('categories.show', $child->slug) }}">{{ $child->name }}</a>
                        @if ($child->children->count() > 0)
                            <ul>
                                @foreach ($child->children as $subChild)
                                    <li><a href="{{ route('categories.show', $subChild->slug) }}">{{ $subChild->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <!-- Articoli della categoria -->
        <h2>Articoli</h2>
        <ul>
            @forelse ($articles as $article)
                <li><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></li>
            @empty
                <p>Nessun articolo disponibile in questa categoria.</p>
            @endforelse
        </ul>
    </div>
@endsection
