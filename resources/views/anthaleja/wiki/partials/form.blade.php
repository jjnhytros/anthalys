@extends('layouts.main')

@section('content')
    <div class="row">
        @if ($isEdit)
            <div class="col-md-3">
                <!-- Sidebar dei template utilizzati -->
                <h3>Template utilizzati</h3>
                <ul>
                    @foreach ($templatesInfo as $template)
                        <li>
                            @if ($template['exists'])
                                <a href="#" style="color: blue;" data-bs-toggle="modal"
                                    data-bs-target="#templateModal{{ $loop->index }}">
                                    {{ $template['name'] }} (modifica)
                                </a>
                                <!-- Modal per modificare il template -->
                                <div class="modal fade" id="templateModal{{ $loop->index }}" tabindex="-1"
                                    aria-labelledby="templateModalLabel{{ $loop->index }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="templateModalLabel{{ $loop->index }}">Modifica
                                                    Template: {{ $template['name'] }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <iframe src="{{ $template['link'] }}" width="100%"
                                                    height="400"></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Chiudi</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if (isset($template['link']))
                                    <a href="{{ $template['link'] }}" style="color: red;">{{ $template['slug'] }} (crea)</a>
                                @else
                                    <span style="color: red;">{{ $template['slug'] }} (link non disponibile)</span>
                                @endif
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="{{ $isEdit ? 'col-md-9' : 'col-md-12' }}">
            <h1>{{ $title }}</h1>

            <form action="{{ $formAction }}" method="POST">
                @csrf
                @if ($isEdit)
                    @method('PUT') <!-- Aggiungi PUT solo se è una modifica -->
                @endif

                <div class="form-group">
                    <label for="code_language">Select Programming Language</label>
                    <select name="code_language" id="code_language" class="form-control">
                        <option value="none">None</option>
                        <option value="python">Python</option>
                        <option value="lua">Lua</option>
                        <option value="c">C</option>
                        <!-- Aggiungi altri linguaggi -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="category_id">Categoria</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">Seleziona Categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $article->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="articleTitle">{{ $nameLabel }}</label>
                    <input type="text" id="articleTitle" name="articleTitle" class="form-control"
                        value="{{ old('articleTitle', $nameValue) }}" required>
                </div>
                @if ($slug)
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="hidden" name="slug" class="form-control" value="{{ $slug }}">
                    </div>
                @endif

                @include('anthaleja.wiki.partials.toolbar')
                <div class="form-group">
                    <label for="content">{{ $contentLabel }}</label>
                    <textarea id="content" name="content" class="form-control" rows="10" required>{{ old('content', $contentValue) }}</textarea>
                </div>

                <!-- Pulsanti di Azione -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-success">
                        {{ $isEdit ? 'Salva Modifiche' : 'Crea' }}
                    </button>
                    <a href="{{ $cancelRoute }}" class="btn btn-secondary">Annulla</a>
                </div>
            </form>
        </div>
    </div>
@endsection
