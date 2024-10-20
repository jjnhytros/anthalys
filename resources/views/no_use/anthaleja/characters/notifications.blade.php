@extends('layouts.main')

@section('content')
    <h1>Notifiche per {{ $character->name }}</h1>

    <ul class="list-group">
        @foreach ($notifications as $notification)
            <li class="list-group-item {{ $notification->read ? 'list-group-item-secondary' : '' }}">
                {{ $notification->message }}
                @if (!$notification->read)
                    <form action="{{ route('notifications.read', ['notification' => $notification->id]) }}" method="POST"
                        class="float-right">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">Segna come letto</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
