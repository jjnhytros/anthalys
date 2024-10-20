@extends('layouts.main')

@section('content')
    <h1>Sistema di Crafting</h1>

    <h2>Ricette Disponibili</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nome Oggetto</th>
                <th>Risorse Necessarie</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recipes as $recipe)
                <tr>
                    <td>{{ $recipe->name }}</td>
                    <td>
                        <ul>
                            @foreach ($recipe->resources as $resource)
                                <li>{{ $resource->name }}: {{ $resource->pivot->quantity }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <form action="{{ route('crafting.craft', ['recipe' => $recipe->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Crea</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
