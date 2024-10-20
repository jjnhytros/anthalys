<div class="list-group-item d-flex justify-content-between align-items-center">
    <div>
        <strong>{{ $request->sender->username }}</strong> has sent you a connection request.
    </div>
    <div>
        <form action="{{ route('rooms.connections.update', $request->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="accepted">
            <button type="submit" class="btn btn-success btn-sm">Accept</button>
        </form>
        <form action="{{ route('rooms.connections.update', $request->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
        </form>
    </div>
</div>
