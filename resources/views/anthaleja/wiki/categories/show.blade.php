@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>{{ $category->name }}</h1>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if ($category->parent)
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', $category->parent->slug) }}">{{ $category->parent->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        <p>{{ $category->description }}</p>

        <!-- Form di Filtraggio -->
        <form method="GET" action="{{ route('categories.show', $category->slug) }}">
            <div class="form-group">
                <label for="from_date">Da:</label>
                <input type="date" name="from_date" id="from_date" class="form-control"
                    value="{{ old('from_date', request('from_date')) }}">
            </div>

            <div class="form-group">
                <label for="to_date">A:</label>
                <input type="date" name="to_date" id="to_date" class="form-control"
                    value="{{ old('to_date', request('to_date')) }}">
            </div>

            <button type="submit" class="btn btn-primary">Filtra</button>
        </form>

        <!-- Sottocategorie -->
        @if ($category->children->count() > 0)
            <h2>Sottocategorie</h2>
            <ul>
                @foreach ($category->children as $child)
                    <li><a href="{{ route('categories.show', $child->slug) }}">{{ $child->name }}</a></li>
                @endforeach
            </ul>
        @endif

        <!-- Articoli della Categoria -->
        <h2>Articoli Correlati</h2>
        <ul>
            @forelse ($articles as $article)
                <li><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></li>
            @empty
                <p>Nessun articolo trovato per il periodo selezionato.</p>
            @endforelse
        </ul>
        {{ $articles->links() }}

    </div>
@endsection
