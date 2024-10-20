@extends('layouts.main')

@section('content')
    <h1>Relazioni di {{ $character->name }}</h1>

    @if ($relationships->count())
        <ul>
            @foreach ($relationships as $relationship)
                <li>
                    <strong>{{ $relationship->relatedCharacter->name }}</strong>
                    - Tipo: {{ $relationship->relationship_type }}
                    - Forza: {{ $relationship->strength }}/100
                    <form action="{{ route('characters.improve-relationship', [$character, $relationship]) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Migliora</button>
                    </form>
                    <form action="{{ route('characters.worsen-relationship', [$character, $relationship]) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Peggiora</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p>Non ci sono relazioni.</p>
    @endif

    <!-- Aggiungi una nuova relazione -->
    <h3>Aggiungi una Nuova Relazione</h3>
    <form action="{{ route('characters.add-relationship', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="related_character_id">Personaggio</label>
            <select name="related_character_id" class="form-control">
                @foreach (\App\Models\Character::where('id', '!=', $character->id)->get() as $otherCharacter)
                    <option value="{{ $otherCharacter->id }}">{{ $otherCharacter->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="relationship_type">Tipo di Relazione</label>
            <select name="relationship_type" class="form-control">
                <option value="friend">Amico</option>
                <option value="rival">Rivale</option>
                <option value="neutral">Neutrale</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi Relazione</button>
    </form>
@endsection
