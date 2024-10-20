@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <h2>Crea un nuovo Sonet</h2>
        <form action="{{ route('sonet.store') }}" method="POST" class="mt-3">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Scrivi qualcosa:</label>
                <textarea name="content" class="form-control" rows="4" placeholder="Cosa hai in mente?" required></textarea>
            </div>
            <div class="form-group">
                <label for="media">Upload Image/Video or Insert URL:</label>
                <input type="file" class="form-control" id="mediaFile" name="media" accept="image/*,video/*">
            </div>

            <div class="form-group">
                <label for="mediaUrl">Or insert media URL:</label>
                <input type="url" class="form-control" id="mediaUrl" name="media_url"
                    placeholder="https://example.com/media">
            </div>

            <!-- Anteprima dinamica per immagine o video -->
            <img id="imagePreview" src="#" alt="Your Image" style="display: none; max-width: 100%;" />
            <video id="videoPreview" controls style="display: none; max-width: 100%;"></video>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="publish_at" class="form-label">Data di Pubblicazione (opzionale)</label>
                        <input type="text" id="publish_at" name="publish_at" class="form-control"
                            placeholder="Seleziona una data e un'ora">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="expires_at">Data di Scadenza (opzionale)</label>
                        <input type="date" id="expires_at" name="expires_at" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="visibility" class="form-label">Visibilità:</label>
                        <select name="visibility" class="form-select" required>
                            <option value="follower">Solo connessioni</option>
                            <option value="public" selected>Pubblico</option>
                            <option value="private">Privato</option>
                            <option value="mentioned">Solo menzionati</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Pubblica</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#publish_at", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today" // Non permette date nel passato
            });
        });
        // Anteprima file locale
        document.getElementById('mediaFile').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                const fileType = file.type;

                if (fileType.startsWith('image/')) {
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('videoPreview').style.display = 'none';
                    document.getElementById('imagePreview').src = URL.createObjectURL(file);
                } else if (fileType.startsWith('video/')) {
                    document.getElementById('videoPreview').style.display = 'block';
                    document.getElementById('imagePreview').style.display = 'none';
                    document.getElementById('videoPreview').src = URL.createObjectURL(file);
                }
            }
        };

        // Anteprima URL
        document.getElementById('mediaUrl').oninput = function() {
            const url = this.value;
            const extension = url.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('videoPreview').style.display = 'none';
                document.getElementById('imagePreview').src = url;
            } else if (['mp4', 'mov', 'ogg', 'webm'].includes(extension)) {
                document.getElementById('videoPreview').style.display = 'block';
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('videoPreview').src = url;
            }
        };
    </script>
@endsection
