@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <h1>{{ $isEdit ? 'Modifica Infobox' : 'Crea Nuovo Infobox' }}</h1>

        <form action="{{ $formAction }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="form-group mb-3">
                <label for="type">Tipo di Infobox</label>
                <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $typeValue) }}">
            </div>

            <div class="form-group mb-3">
                <label for="content">Contenuto HTML del Template</label>
                <textarea name="content" id="content" class="form-control" rows="10">{{ old('content', $contentValue) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label for="optional_fields">Campi Opzionali (separa con virgola)</label>
                <input type="text" name="optional_fields" id="optional_fields" class="form-control"
                    value="{{ old('optional_fields', implode(',', $optionalFields ?? [])) }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Aggiorna Infobox' : 'Crea Infobox' }}</button>
        </form>
    </div>
@endsection
