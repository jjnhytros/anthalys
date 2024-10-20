@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Fusione degli articoli</h1>
        <form action="{{ route('articles.merge', [$article1->id, $article2->id]) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title">Titolo dell'articolo fuso</label>
                <select name="title" id="title" class="form-control">
                    <option value="{{ $article1->title }}">{{ $article1->title }}</option>
                    <option value="{{ $article2->title }}">{{ $article2->title }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="content">Contenuto dell'articolo fuso</label>
                <textarea name="content" id="content" class="form-control" rows="10">{{ $article1->content }}\n\n{{ $article2->content }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Fondi articoli</button>
        </form>
    </div>
@endsection
