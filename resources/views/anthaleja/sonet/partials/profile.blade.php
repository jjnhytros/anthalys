<!-- resources/views/anthaleja/sonet/partials/profile.blade.php -->
@if (Auth::user()->character->isFollowing($character))
    <form action="{{ route('follow.unfollow', $character->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Disconnetti</button>
    </form>
@else
    <form action="{{ route('follow.follow', $character->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Connetti</button>
    </form>
@endif
@include('components.reputation_form', ['character' => $profile->character])
