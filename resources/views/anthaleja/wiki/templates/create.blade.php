@extends('layouts.main')

@section('content')
    <h1>Crea un Nuovo Template</h1>

    <form action="{{ route('templates.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Nome del Template</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $templateName) }}" required>
        </div>

        <div class="form-group">
            <label for="content">Contenuto del Template</label>
            <textarea id="content" name="content" class="form-control" rows="10" required>{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Crea Template</button>
    </form>
@endsection
