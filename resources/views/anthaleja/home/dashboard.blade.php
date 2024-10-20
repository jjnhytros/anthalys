@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Dashboard del Character</h1>
        <p>Benvenuto, {{ $character->username }}!</p> <!-- Mostra lo username del character -->
        <p>Nome completo: {{ $character->first_name }} {{ $character->last_name }}</p>
        <p>Saldo attuale: {!! athel($character->cash) !!}</p> <!-- Mostra i dati relativi al character -->
    </div>
@endsection
