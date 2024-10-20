@extends('layouts.main')

@section('content')
    <h1>Marketplace</h1>

    <!-- Form di ricerca -->
    <form method="GET" action="{{ route('marketplace.index') }}">
        <input type="text" name="search" placeholder="Cerca oggetti">
        <select name="type">
            <option value="">Tutti i tipi</option>
            <option value="resource">Risorsa</option>
            <option value="crafted">Craftato</option>
        </select>
        <select name="region" class="form-select">
            <option value="">Seleziona Regione</option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}">{{ $region->name }}</option>
            @endforeach
        </select>
        <input type="number" name="min_price" class="form-control" placeholder="Prezzo Minimo" min="0"
            step="0.01">
        <input type="number" name="max_price" class="form-control" placeholder="Prezzo Massimo" min="0"
            step="0.01">
        <button type="submit">Filtra</button>
    </form>

    <div class="row g-4">
        @foreach ($items as $item)
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="{{ asset($item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text mb-4">{{ athel($item->price) }}</p>
                        <a href="{{ route('marketplace.show', $item) }}" class="btn btn-primary mt-auto">Dettagli</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
