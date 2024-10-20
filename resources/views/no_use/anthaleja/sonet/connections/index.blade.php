@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.connections') }}</h1>

    @if ($connections->isEmpty())
        <p>{{ __('messages.no_connections') }}</p>
    @else
        <ul class="list-group">
            @foreach ($connections->where('type', 'personal') as $connection)
                <li class="list-group-item">
                    {{ $connection->connectedCharacter->first_name }} {{ $connection->connectedCharacter->last_name }}
                    <form
                        action="{{ route('connections.block', [$connection->character_id, $connection->connected_character_id]) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">{{ __('messages.block') }}</button>
                    </form>
                </li>
            @endforeach
        </ul>

        <h2>{{ __('messages.professional_connections') }}</h2>
        <ul class="list-group">
            @foreach ($connections->where('type', 'professional') as $connection)
                <li class="list-group-item">
                    {{ $connection->connectedCharacter->first_name }} {{ $connection->connectedCharacter->last_name }}
                    <form
                        action="{{ route('connections.block', [$connection->character_id, $connection->connected_character_id]) }}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">{{ __('messages.block') }}</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
