@extends('layouts.main')

@section('content')
    <div class="container text-center">
        <h1>Welcome to {{ config('app.name') }}</h1>
        <p>This is your homepage. Test the navbar functionality and other Livewire components here.</p>

        @auth
            @include('layouts.partials.navbar_phone')
        @endauth
        {{-- Aggiungi altri contenuti che desideri visualizzare nella homepage --}}
    </div>
@endsection
