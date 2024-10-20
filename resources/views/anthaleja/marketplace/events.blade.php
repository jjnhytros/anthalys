@extends('layouts.main')

@section('content')
    <h1>Eventi Speciali del Marketplace</h1>

    <ul>
        @foreach ($events as $event)
            <li>{{ $event->name }} - Attivo fino al {{ $event->end_time->format('d-m-Y H:i') }}</li>
        @endforeach
    </ul>
@endsection
