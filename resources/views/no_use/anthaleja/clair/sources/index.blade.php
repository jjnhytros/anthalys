@extends('layouts.main')

@section('content')
    <h1>Elenco delle Fonti</h1>

    <a href="{{ route('sources.create') }}" class="btn btn-primary">Aggiungi una nuova fonte</a>

    <ul>
        @foreach ($sources as $source)
            <li>
                <a href="{{ route('sources.show', $source->id) }}">{{ $source->title }}</a>
                <span> - {{ $source->type }}</span>
                <form action="{{ route('sources.destroy', $source->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                </form>
                <a href="{{ route('sources.edit', $source->id) }}" class="btn btn-warning btn-sm">Modifica</a>
            </li>
        @endforeach
    </ul>
@endsection
