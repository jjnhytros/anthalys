@extends('layouts.admin')

@section('content')
    <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}"
        method="POST">
        @csrf
        @if (isset($category))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name">Nome della Categoria</label>
            <input type="text" name="name" id="name" class="form-control"
                value="{{ old('name', $category->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="parent_id">Categoria Principale</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">Nessuna (Categoria Principale)</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="btn btn-primary">{{ isset($category) ? 'Aggiorna Categoria' : 'Crea Categoria' }}</button>
    </form>
@endsection
