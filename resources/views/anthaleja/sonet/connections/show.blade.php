@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Connection Details</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $connection->connectedCharacter->username }}</h5>
                <p class="card-text">Status: {{ ucfirst($connection->status) }}</p>
                <p class="card-text">Connection since: {{ $connection->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <a href="{{ route('rooms.connections.index') }}" class="btn btn-secondary mt-3">Back to Connections</a>
    </div>
@endsection
