@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.fridge_contents') }}</h1>

    <ul>
        @foreach ($character->inventory->where('location', 'fridge') as $item)
            <li>
                <strong>{{ $item->item_name }}</strong> - {{ __('messages.quantity') }}: {{ $item->quantity }}
                @if ($item->expiration_date)
                    <span class="text-muted">{{ __('messages.expires_on') }}:
                        {{ $item->expiration_date->format('d/m/Y') }}</span>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
