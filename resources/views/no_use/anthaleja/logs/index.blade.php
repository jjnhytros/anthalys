@extends('layouts.main')

@section('content')
    <h1>Log degli Eventi</h1>

    <ul class="list-group">
        @foreach ($logs as $log)
            <li class="list-group-item">
                {{ $log->event }} - {{ $log->created_at }}
            </li>
        @endforeach
    </ul>
@endsection
