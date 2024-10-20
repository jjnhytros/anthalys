@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Connections</h2>
        <div class="list-group">
            @foreach ($connections as $connection)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $connection->connectedCharacter->username }}</strong>
                        <span class="badge bg-info">{{ ucfirst($connection->status) }}</span>
                    </div>
                    <div>
                        @if ($connection->status === 'pending')
                            <form action="{{ route('rooms.connections.update', $connection->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="btn btn-success btn-sm">Accept</button>
                            </form>
                            <form action="{{ route('rooms.connections.update', $connection->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        @else
                            <form action="{{ route('rooms.connections.destroy', $connection->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-warning btn-sm">Remove</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
