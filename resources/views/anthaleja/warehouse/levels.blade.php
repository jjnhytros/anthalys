{{-- resources/views/anthaleja/warehouse/levels.blade.php --}}
@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 class="mt-4">Magazzino Sotterraneo - Livelli</h1>

        <div class="accordion" id="warehouseLevelsAccordion">
            @foreach ($levels as $level)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $level->id }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $level->id }}" aria-expanded="false"
                            aria-controls="collapse{{ $level->id }}">
                            {{ $level->level_name }} - Profondità: {{ $level->depth }} metri
                        </button>
                    </h2>
                    <div id="collapse{{ $level->id }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $level->id }}" data-bs-parent="#warehouseLevelsAccordion">
                        <div class="accordion-body">
                            <div id="gridContainer{{ $level->id }}">
                                <!-- La griglia verrà caricata dinamicamente qui -->
                                Caricamento dinamico in corso...
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal per visualizzare i dati della cella -->
    <div class="modal fade" id="cellDataModal" tabindex="-1" aria-labelledby="cellDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cellDataModalLabel">Dati della Cella</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cellDataContent">
                        <!-- I dati della cella verranno caricati qui -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordions = document.querySelectorAll('.accordion-collapse');

            accordions.forEach(accordion => {
                // Caricamento della griglia all'apertura dell'accordion
                accordion.addEventListener('shown.bs.collapse', function() {
                    const collapseId = this.getAttribute('id');
                    const levelId = collapseId.replace('collapse', '');
                    const gridContainer = document.getElementById('gridContainer' + levelId);

                    if (!gridContainer.dataset.loaded) {
                        fetch(`/warehouse/levels/${levelId}/grid`)
                            .then(response => response.text())
                            .then(html => {
                                gridContainer.innerHTML = html;
                                gridContainer.dataset.loaded = true;
                            })
                            .catch(error => console.error('Errore nel caricamento della griglia:',
                                error));
                    }
                });

                // Svuotare la griglia quando l'accordion viene chiuso
                accordion.addEventListener('hidden.bs.collapse', function() {
                    const collapseId = this.getAttribute('id');
                    const levelId = collapseId.replace('collapse', '');
                    const gridContainer = document.getElementById('gridContainer' + levelId);

                    gridContainer.innerHTML = ''; // Svuota il contenuto della griglia
                    delete gridContainer.dataset.loaded;
                });
            });

            // Funzione per aprire il modal e caricare i dati della cella
            window.loadCellData = function(levelId, x, y) {
                fetch(`/warehouse/levels/${levelId}/cells/${x}/${y}`)
                    .then(response => response.json())
                    .then(data => {
                        const modalTitle = document.getElementById('cellDataModalLabel');
                        const modalContent = document.getElementById('cellDataContent');

                        modalTitle.innerHTML = `Dati della Cella (${x}, ${y}) - Livello ${levelId}`;
                        modalContent.innerHTML = `
                            <p><strong>Item:</strong> ${data.item}</p>
                            <p><strong>Quantità:</strong> ${data.quantity}</p>
                        `;

                        const cellDataModal = new bootstrap.Modal(document.getElementById('cellDataModal'));
                        cellDataModal.show();
                    })
                    .catch(error => console.error('Errore nel caricamento dei dati della cella:', error));
            }
        });
    </script>
@endsection
