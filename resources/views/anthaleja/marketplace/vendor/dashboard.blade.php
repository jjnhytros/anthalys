@extends('layouts.main')

@section('content')
    <h1>Dashboard del Marketplace</h1>

    <h2>Offerte ricevute</h2>
    <ul>
        @foreach ($items as $item)
            @foreach ($item->offers->where('status', 'pending') as $offer)
                <li>
                    Offerta di {{ athel($offer->offer_price) }} da {{ $offer->buyer->name }}
                    <form action="{{ route('offers.accept', $offer) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success">Accetta</button>
                    </form>
                    <form action="{{ route('offers.reject', $offer) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Rifiuta</button>
                    </form>
                </li>
            @endforeach
        @endforeach
    </ul>
@endsection
