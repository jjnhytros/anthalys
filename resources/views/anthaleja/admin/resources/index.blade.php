@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1>Gestione Risorse dei Personaggi</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Personaggio</th>
                    <th>Risorse</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($characters as $character)
                    <tr>
                        <td>{{ $character->first_name }} {{ $character->last_name }}</td>
                        <td>
                            <form action="{{ route('admin.resources.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="character_id" value="{{ $character->id }}">

                                @foreach ($character->resources as $resource => $value)
                                    <div class="mb-2">
                                        <label for="{{ $resource }}">{{ ucfirst($resource) }}:</label>
                                        <input type="number" name="resources[{{ $resource }}]"
                                            value="{{ $value }}" min="0" max="100" class="form-control">
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary mt-2">Aggiorna Risorse</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.resources.index') }}" class="btn btn-info">Reset</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .container {
            max-width: 900px;
        }

        table {
            margin-top: 20px;
        }
    </style>
@endsection
