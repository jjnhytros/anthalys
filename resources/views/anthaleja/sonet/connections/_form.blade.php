<form action="{{ route('rooms.connections.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="recipient_id">Recipient Username</label>
        <select name="recipient_id" class="form-control" required>
            @foreach ($characters as $character)
                <option value="{{ $character->id }}">{{ $character->username }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Send Connection Request</button>
</form>
