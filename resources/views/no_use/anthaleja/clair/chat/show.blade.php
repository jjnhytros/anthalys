@extends('layouts.main')

@section('content')
    <h1>Dettagli della Conversazione</h1>

    @foreach ($interactions as $interaction)
        <p><strong>Tu:</strong> {{ $interaction->message }}</p>
        <p><strong>AI:</strong> {{ $interaction->response }}</p>
        <hr>
    @endforeach
@endsection
