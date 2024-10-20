@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 class="mt-4">Energy Management - Warehouse</h1>

        <div class="alert alert-info">
            <strong>Energy Status:</strong> {{ $energyStatus }}
        </div>

        <div class="alert alert-warning">
            <strong>Environmental Impact:</strong> {{ $environmentalImpact }}
        </div>

        <div class="alert alert-success">
            <strong>Compensation Status:</strong> {{ $compensationStatus }}
        </div>
    </div>
@endsection
