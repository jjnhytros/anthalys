@extends('layouts.main')

@section('content')
    <h1>Create New Article</h1>

    @include('anthaleja.wiki.partials.toolbar')

    <form action="{{ route('articles.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="code_language">Select Programming Language</label>
            <select name="code_language" id="code_language" class="form-control">
                <option value="none">None</option>
                <option value="python">Python</option>
                <option value="lua">Lua</option>
                <option value="c">C</option>
                <!-- Aggiungi altri linguaggi -->
            </select>
        </div>
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="content">Content (Markdown)</label>
            <textarea id="content" name="content" class="form-control" rows="10" required></textarea>
        </div>

        <div class="form-group" style="display:none;" id="preview"></div> <!-- Anteprima nascosta inizialmente -->

        <button type="submit" class="btn btn-primary">Publish</button>
    </form>
@endsection
