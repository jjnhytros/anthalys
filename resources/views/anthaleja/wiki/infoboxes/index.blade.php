@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <h1>Gestione Infobox</h1>
        <a href="{{ route('infoboxes.create') }}" class="btn btn-primary mb-3">Crea Nuovo Infobox</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($infoboxes as $infobox)
                    <tr>
                        <td>{{ $infobox->type }}</td>
                        <td>
                            <a href="{{ route('infoboxes.edit', $infobox->id) }}" class="btn btn-warning">Modifica</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
