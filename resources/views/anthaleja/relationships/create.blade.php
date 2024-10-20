@extends('layouts.main')

@section('content')
    <h2>Add Relationship</h2>
    <form action="{{ route('relationships.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="related_character_id">Character:</label>
            <select name="related_character_id" id="related_character_id" class="form-control">
                @foreach ($characters as $character)
                    <option value="{{ $character->id }}">{{ $character->username }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="relationship_type">Relationship Type:</label>
            <select name="relationship_type" id="relationship_type" class="form-control">
                <option value="friend">Friend</option>
                <option value="sibling">Sibling</option>
                <option value="spouse">Spouse</option>
                <option value="colleague">Colleague</option>
                <!-- Add more options as needed -->
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Relationship</button>
    </form>
@endsection
