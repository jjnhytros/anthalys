<!-- Modal unico per la creazione di post, commenti o risposte -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm p-2">
        <div class="modal-content p-1">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Aggiungi un contenuto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenuto dinamico del Sonet o del Commento -->
                <div id="contextContent" class="mb-3" style="display: none;">
                    <p id="contextText" class="text-muted"></p>
                </div>

                <!-- Form dinamico per la creazione -->
                <form id="actionForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="parent_id" id="parentId">
                    <input type="hidden" name="sonet_post_id" id="sonetPostId">

                    <div class="mb-3" id="postContentContainer">
                        <textarea name="content" class="form-control" rows="4" placeholder="Scrivi il tuo contenuto..." required></textarea>
                    </div>

                    <!-- Campo per il caricamento dei media (immagini o video) -->
                    <div class="mb-3" id="mediaField">
                        <label for="media" class="form-label">Carica un'immagine o un video:</label>
                        <input type="file" name="media" id="media" class="form-control"
                            accept="image/*,video/*">
                    </div>
                    <div id="mediaPreview" class="mt-3"></div>

                    <!-- Campo per la data di pubblicazione (opzionale) -->
                    <div class="mb-3" id="publishAtField" style="display: none;">
                        <label for="publish_at" class="form-label">Pubblica il post a:</label>
                        <input type="datetime-local" id="publish_at" name="publish_at" class="form-control">
                    </div>

                    <!-- Campo per la visibilità -->
                    <div class="mb-3" id="visibilityField" style="display: none;">
                        <label for="visibility" class="form-label">Visibilità:</label>
                        <select name="visibility" class="form-select">
                            <option value="follower">Solo connessioni</option>
                            <option value="public">Pubblico</option>
                            <option value="private">Privato</option>
                            <option value="mentioned">Solo menzionati</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitButton">Invia</button>
                </form>
            </div>
        </div>
    </div>
</div>
