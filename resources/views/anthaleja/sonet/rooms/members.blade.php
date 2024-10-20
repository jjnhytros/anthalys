{{-- resources/views/anthaleja/sonet/rooms/members.blade.php --}}
@foreach ($room->members as $member)
    <div class="mb-3">
        <p>{{ $member->character->username }} - Ruolo attuale: {{ ucfirst($member->role) }}</p>
        @if ($room->creator->id === Auth::user()->character->id || Auth::user()->character->id === $member->character->id)
            <form action="{{ route('rooms.members.updateRole', $room) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="character_id" value="{{ $member->character_id }}">
                <select name="role" class="form-select" required>
                    <option value="member" {{ $member->role === 'member' ? 'selected' : '' }}>Member</option>
                    <option value="moderator" {{ $member->role === 'moderator' ? 'selected' : '' }}>Moderator</option>
                    <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="btn btn-primary mt-2">Aggiorna Ruolo</button>
            </form>
        @endif
    </div>
@endforeach
