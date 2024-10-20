@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.leaderboard') }}</h1>

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('messages.character_name') }}</th>
                <th>{{ __('messages.total_points') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaderboard as $character)
                <tr>
                    <td>{{ $character->first_name }} {{ $character->last_name }}</td>
                    <td>{{ $character->total_points }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
