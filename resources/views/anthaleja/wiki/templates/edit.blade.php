@extends('layouts.main')

@section('content')
    <h1>Modifica Template: {{ $template->name }}</h1>

    <form action="{{ route('templates.update', $template->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome del Template</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $template->name) }}" required>
        </div>

        <div class="form-group">
            <label for="content">Contenuto del Template</label>
            <textarea id="content" name="content" class="form-control" rows="10" required>{{ old('content', $template->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Salva Modifiche</button>
    </form>
@endsection
