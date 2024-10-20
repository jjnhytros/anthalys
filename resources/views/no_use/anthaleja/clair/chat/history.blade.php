@extends('layouts.main')

@section('content')
    <h1>Storico delle conversazioni</h1>

    <ul>
        @foreach ($conversations as $conversation)
            <li>
                <a href="{{ route('chat.showConversation', $conversation->id) }}">
                    Conversazione iniziata il {{ $conversation->created_at }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
