@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Benvenuto nel sito!</h1>
        <p>Questa è la homepage visibile ai visitatori non autenticati.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Accedi</a>
    </div>
@endsection
