@extends('layouts.main')

@section('content')
    <h1>Regions</h1>
    <ul class="list-group">
        @foreach ($regions as $region)
            <li class="list-group-item">{{ $region->name }} - {{ $region->description }}</li>
        @endforeach
    </ul>

    <h2>Add a New Region</h2>
    <form action="{{ route('regions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Region Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Region</button>
    </form>
@endsection
