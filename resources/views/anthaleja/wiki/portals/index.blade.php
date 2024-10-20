@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Portali Tematici</h1>
        <div class="row">
            @foreach ($portals as $portal)
                <div class="col-md-4">
                    <div class="card mb-3">
                        @if ($portal->cover_image)
                            <img src="{{ asset('storage/' . $portal->cover_image) }}" class="card-img-top"
                                alt="{{ $portal->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $portal->name }}</h5>
                            <p class="card-text">{{ Str::limit($portal->description, 100) }}</p>
                            <a href="{{ route('portals.show', $portal->id) }}" class="btn btn-primary">Scopri di più</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
