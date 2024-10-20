<!-- Colonna destra: informazioni sul profilo o post popolari -->
<aside class="col-md-3 d-none d-md-block bg-light">
    <div class="position-sticky">
        <h4>Profilo</h4>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ Auth::user()->character->username }}</h5>
                <p class="card-text">Statistiche, connessioni, etc.</p>
            </div>
        </div>
        <h5 class="mt-4">Post Popolari</h5>
        <!-- Aggiungi contenuto aggiuntivo, come post o notizie -->
    </div>
</aside>
