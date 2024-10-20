@extends('layouts.main')

@section('content')
    <h1>Elenco Template</h1>

    <ul>
        @foreach ($templates as $template)
            <li>
                <a href="{{ route('templates.edit', $template->slug) }}">{{ $template->name }}</a>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('templates.create') }}" class="btn btn-primary">Crea un Nuovo Template</a>
@endsection
