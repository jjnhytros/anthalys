@extends('layouts.main')

@section('content')
    <h1>Personalizzazione di {{ $character->name }}</h1>

    <form action="{{ route('characters.customize', $character) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="outfit">Abbigliamento</label>
            <select name="outfit" class="form-control">
                <option value="default" {{ $customization->outfit == 'default' ? 'selected' : '' }}>Default</option>
                <option value="formal" {{ $customization->outfit == 'formal' ? 'selected' : '' }}>Formale</option>
                <option value="casual" {{ $customization->outfit == 'casual' ? 'selected' : '' }}>Casual</option>
            </select>
        </div>

        <div class="form-group">
            <label for="hair_style">Acconciatura</label>
            <select name="hair_style" class="form-control">
                <option value="short" {{ $customization->hair_style == 'short' ? 'selected' : '' }}>Corto</option>
                <option value="long" {{ $customization->hair_style == 'long' ? 'selected' : '' }}>Lungo</option>
                <option value="curly" {{ $customization->hair_style == 'curly' ? 'selected' : '' }}>Ricci</option>
            </select>
        </div>

        <div class="form-group">
            <label for="accessory">Accessorio</label>
            <input type="text" name="accessory" class="form-control" value="{{ $customization->accessory }}">
        </div>

        <button type="submit" class="btn btn-primary">Salva Personalizzazione</button>
    </form>
@endsection
