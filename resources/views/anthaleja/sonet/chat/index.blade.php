@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Chat Rooms</h1>
        <ul>
            @foreach ($chatRooms as $room)
                <li><a href="{{ route('chat.show', $room->id) }}">{{ $room->name }}</a></li>
            @endforeach
        </ul>

        <form action="{{ route('chat.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="New Chat Room">
            <button type="submit">Create</button>
        </form>
        <form action="{{ route('chat.createGroup') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Nome del gruppo">
            <select name="participants[]" multiple>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->username }}</option>
                @endforeach
            </select>
            <button type="submit">Crea Chat di Gruppo</button>
        </form>

    </div>
@endsection
