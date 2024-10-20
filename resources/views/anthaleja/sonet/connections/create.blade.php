@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Create Connection Request</h2>
        @include('anthaleja.sonet.connections._form', ['characters' => $characters])
    </div>
@endsection
