@extends('layouts.main')

@section('content')
    <h2>Relationships</h2>
    <a href="{{ route('relationships.create') }}" class="btn btn-primary">Add Relationship</a>

    <ul>
        @foreach ($relationships as $relationship)
            <li>{{ $relationship->relatedCharacter->username }} - {{ ucfirst($relationship->relationship_type) }}
                <form action="{{ route('relationships.destroy', $relationship) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
