@extends('layouts.main')

@section('title', 'Create Article')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{ $title }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ $formAction }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $nameValue) }}">
                </div>
                <!-- Input group verticale per la toolbar e textarea -->
                <div class="input-group d-flex flex-column align-content-end flex-wrap bg-light">
                    <!-- Toolbar inclusa -->
                    <div class="m-1">
                        @include('anthaleja.wiki.partials.toolbar')
                    </div>
                    <textarea name="content" id="content" class="form-control w-100" rows="10">{{ old('content', $contentValue) }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="render_infobox" id="render_infobox" class="form-check-input"
                            value="1" {{ old('render_infobox', $renderInfoboxValue) ? 'checked' : '' }}>
                        <label class="form-check-label" for="render_infobox">Render Infobox (Mostra l'infobox
                            elaborato)</label>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Save Article</button>
                </div>
            </form>
        </div>
    </div>

    {{-- <h3>Generate Article</h3>
    <form action="{{ route('articles.generate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="seed_word">Seed Word</label>
            <input type="text" name="seed_word" id="seed_word" class="form-control" value="{{ old('seed_word') }}">
        </div>

        <button type="submit" class="btn btn-secondary">Generate Article</button>
    </form> --}}
@endsection
