@extends('layouts.main')

@section('content')
    <div class="container">
        <form method="GET" action="{{ route('portals.show', $portal->id) }}">
            <select name="sort_by" class="form-control">
                <option value="created_at_desc">Data (recenti)</option>
                <option value="created_at_asc">Data (meno recenti)</option>
                <option value="title_asc">Titolo (A-Z)</option>
                <option value="title_desc">Titolo (Z-A)</option>
            </select>
            <div class="form-group">
                <label for="from_date">Da:</label>
                <input type="date" name="from_date" id="from_date" class="form-control">
            </div>

            <div class="form-group">
                <label for="to_date">A:</label>
                <input type="date" name="to_date" id="to_date" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Filtra</button>
        </form>

        <h1>{{ $portal->name }}</h1>
        <p>{{ $portal->description }}</p>
        @if ($portal->cover_image)
            <img src="{{ asset('storage/' . $portal->cover_image) }}" class="img-fluid" alt="{{ $portal->name }}">
        @endif

        <h2>Articoli Correlati</h2>
        <ul>
            @foreach ($portal->articles as $article)
                <li><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></li>
            @endforeach
        </ul>
    </div>
@endsection
