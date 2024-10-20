@extends('layouts.main')

@section('content')
    <h1>Modifica Articolo: {{ $article->title }}</h1>

    <form action="{{ route('articles.update', $article->slug) }}" method="POST">
        @csrf
        @method('PUT') <!-- Specifica il metodo PUT per l'aggiornamento -->

        <div class="form-group">
            <label for="title">Titolo</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ $article->title }}" required>
        </div>

        <div class="form-group">
            <label for="content">Contenuto (Markdown)</label>
            <textarea id="content" name="content" class="form-control" rows="10" required>{{ $article->content }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Salva Modifiche</button>
    </form>

    <hr>

    <h3>Template utilizzati in questo articolo:</h3>
    <ul>
        @foreach ($templatesInfo as $template)
            <li>
                @if ($template['exists'])
                    <a href="{{ $template['link'] }}" style="color: blue;">{{ $template['name'] }}</a> (esistente)
                @else
                    <a href="{{ $template['link'] }}" style="color: red;">{{ $template['name'] }}</a> (non esistente)
                @endif
            </li>
        @endforeach
    </ul>
@endsection
