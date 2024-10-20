@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Edit Connection Request</h2>
        <form action="{{ route('rooms.connections.update', $connection->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="status">Connection Status</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $connection->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ $connection->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ $connection->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Request</button>
        </form>
    </div>
@endsection
