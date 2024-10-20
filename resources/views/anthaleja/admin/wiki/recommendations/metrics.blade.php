@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Monitoraggio delle Raccomandazioni</h1>

        <div class="metrics">
            <h3>Precisione delle Raccomandazioni: {{ $accuracy }}%</h3>
            <h3>Tasso di Conversione delle Raccomandazioni: {{ $conversionRate }}%</h3>
        </div>
    </div>
@endsection
