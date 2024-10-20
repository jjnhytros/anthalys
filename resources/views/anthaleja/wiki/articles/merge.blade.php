@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Fondi Articoli</h1>

        <form action="{{ route('articles.merge', [$article1->id, $article2->id]) }}" method="POST">
            @csrf

            <!-- Selezione del titolo -->
            <div class="form-group mb-4">
                <label for="title" class="form-label">Scegli il titolo da mantenere</label>
                <select name="title" id="title" class="form-control">
                    <option value="{{ $article1->title }}">{{ $article1->title }}</option>
                    <option value="{{ $article2->title }}">{{ $article2->title }}</option>
                </select>
            </div>

            <div class="row">
                <!-- Colonna per Articolo 1 -->
                <div class="col-md-6">
                    <h3>Sezioni di {{ $article1->title }}</h3>
                    <div class="list-group">
                        @foreach ($sections1 as $index => $section)
                            <div class="list-group-item">
                                <textarea name="sections[content][]" class="form-control" rows="4">{{ $section }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colonna per Articolo 2 -->
                <div class="col-md-6">
                    <h3>Sezioni di {{ $article2->title }}</h3>
                    <div class="list-group">
                        @foreach ($sections2 as $index => $section)
                            <div class="list-group-item">
                                <textarea name="sections[content][]" class="form-control" rows="4">{{ $section }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <h3 class="mt-5">Suggerimenti di fusione basati sulla somiglianza</h3>
            <div class="list-group">
                @foreach ($suggestedMerges as $suggestion)
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-md-5">
                                <strong>Sezione 1:</strong>
                                <p>{{ $suggestion['section1'] }}</p>
                            </div>
                            <div class="col-md-5">
                                <strong>Sezione 2:</strong>
                                <p>{{ $suggestion['section2'] }}</p>
                            </div>
                            <div class="col-md-2">
                                <p>Somiglianza: {{ $suggestion['similarity'] }}%</p>
                                <div class="progress">
                                    <div class="progress-bar
                                    @if ($suggestion['similarity'] >= 80) bg-success
                                    @elseif($suggestion['similarity'] >= 50)
                                        bg-warning
                                    @else
                                        bg-danger @endif
                                    "
                                        role="progressbar" style="width: {{ $suggestion['similarity'] }}%"
                                        aria-valuenow="{{ $suggestion['similarity'] }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $suggestion['similarity'] }}%
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm mt-2"
                                    onclick="mergeSuggestedSections({{ $suggestion['index1'] }}, {{ $suggestion['index2'] }})">Unisci</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Fondi Articoli</button>
            </div>
        </form>
    </div>

    <script>
        // Funzione per unire le sezioni suggerite automaticamente
        function mergeSuggestedSections(index1, index2) {
            let section1 = document.querySelectorAll('textarea[name="sections[content][]"]')[index1];
            let section2 = document.querySelectorAll('textarea[name="sections[content][]"]')[index2];

            section1.value += "\n\n" + section2.value;
            section2.value = ''; // Pulisce la sezione dell'Articolo 2
        }
    </script>

    <style>
        .list-group-item {
            margin-bottom: 15px;
        }

        textarea {
            resize: vertical;
        }
    </style>
@endsection
