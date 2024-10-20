@extends('anthaleja.wiki.partials.main_layout')

@section('article_content')
    <h1>{{ $article->title }}</h1>

    <!-- Pulsante di modifica con classe personalizzata -->
    <span class="breadcrumb-edit-button">
        <a href="{{ route('articles.edit', $article->slug) }}" class="btn btn-primary custom-edit-btn">Modifica</a>
    </span>

    <div class="article-content">
        {!! $content !!}
    </div>
    @if ($article->category)
        <p>Categoria: <a href="{{ route('categories.show', $article->category->slug) }}">{{ $article->category->name }}</a>
        </p>
    @endif

    <!-- Modal per chiedere se creare l'articolo -->
    <div class="modal fade" id="createArticleModal" tabindex="-1" aria-labelledby="createArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createArticleModalLabel">Crea un nuovo articolo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    L'articolo "<span id="articleTitle"></span>" non esiste. Vuoi crearlo?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <a id="createArticleLink" href="#" class="btn btn-primary">Crea Articolo</a>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var nonExistentLinks = document.querySelectorAll('.non-existent-link');

            nonExistentLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Previeni la navigazione del link

                    // Ottieni il titolo della pagina dal link
                    var articleTitle = link.textContent.trim();

                    // Crea lo slug dal titolo
                    var articleSlug = articleTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-')
                        .replace(/(^-|-$)/g, '');

                    // Aggiorna il contenuto del modal con il titolo della pagina
                    document.getElementById('articleTitle').textContent = articleTitle;

                    // Modifica il link del pulsante per creare l'articolo
                    // Usando `encodeURIComponent` per codificare correttamente la query string
                    document.getElementById('createArticleLink').setAttribute('href',
                        '/articles/create?slug=' + encodeURIComponent(articleSlug) +
                        '&articleTitle=' + encodeURIComponent(articleTitle));

                    // Mostra il modal
                    var myModal = new bootstrap.Modal(document.getElementById(
                        'createArticleModal'));
                    myModal.show();
                });
            });
        });
    </script>
@endsection
