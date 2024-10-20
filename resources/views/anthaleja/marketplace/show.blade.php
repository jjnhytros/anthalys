@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <img src="{{ asset($item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">Prezzo: {{ athel($item->price) }}</p>

                        <!-- Form per fare un'offerta -->
                        <form action="{{ route('marketplace.makeOffer', $item) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="offer_price" class="form-label">Fai un'Offerta:</label>
                                <input type="number" name="offer_price" step="0.01" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Invia Offerta</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <a href="{{ route('marketplace.index') }}" class="btn btn-secondary mt-4">Torna al Marketplace</a>
            </div>
        </div>
    </div>
@endsection
