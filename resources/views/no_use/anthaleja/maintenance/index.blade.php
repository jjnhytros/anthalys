@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.object_maintenance') }}</h1>

    <ul>
        @foreach ($character->objekts as $objekt)
            <li>
                <strong>{{ $objekt->name }}</strong> - {{ __('messages.condition') }}: {{ $objekt->condition }}%
                <form action="{{ route('maintenance.repair', $objekt->id) }}" method="POST">
                    @csrf
                    <label>{{ __('messages.repair_amount') }}</label>
                    <input type="number" name="repair_amount" value="10" min="1" max="100">
                    <button type="submit" class="btn btn-primary">{{ __('messages.repair_object') }}</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
