@extends('layouts.main')

@section('content')
    <h1>Specializzazioni</h1>

    <h2>Seleziona una Specializzazione</h2>

    <form action="{{ route('work.specialize') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="specialization">Specializzazione</label>
            <select name="specialization" id="specialization" class="form-control">
                <option value="Maestro Fabbro">Maestro Fabbro</option>
                <option value="Esperto di Commercio">Esperto di Commercio</option>
                <option value="Produttore di Risorse">Produttore di Risorse</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Seleziona Specializzazione</button>
    </form>
@endsection
