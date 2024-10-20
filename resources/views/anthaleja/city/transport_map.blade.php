@extends('layouts.main')

@section('content')
    <style>
        .cell {
            width: 2.5vw;
            height: 2.5vw;
            position: relative;
        }

        @media (max-width: 768px) {
            .cell {
                width: 5vw;
                height: 5vw;
            }
        }

        @media (max-width: 480px) {
            .cell {
                width: 8vw;
                height: 8vw;
            }
        }

        .palina {
            position: absolute;
            top: 0;
            left: 0;
            background-color: white;
            color: black;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            line-height: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>

    <div class="container-fluid mb-3">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h1 class="text-center mb-4">Mappa dei Trasporti di Anthalys</h1>
                <!-- Pulsante per tornare alla mappa della città -->
                <a href="{{ route('city.map') }}" class="btn btn-secondary mb-3">Torna alla Mappa della Città</a>

                <div class="d-flex flex-wrap justify-content-center">
                    @foreach ($squares as $index => $square)
                        <!-- Cella con colore di sfondo per le fermate e i percorsi -->
                        <div class="cell m-0 border border-dark text-center"
                            style="background: {{ $square['background_style'] ?? 'none' }};" data-id="{{ $square['id'] }}"
                            data-x="{{ $square['x_coordinate'] }}" data-y="{{ $square['y_coordinate'] }}"
                            data-sector="{{ $square['sector_name'] }}" data-type="{{ $square['type'] }}"
                            data-description="{{ $square['description'] }}" onclick="openModal(this)">
                            <!-- Se è una fermata, mostra una palina -->
                            @if ($square['background_style'])
                                <div class="palina">{{ $index + 1 }}</div>
                            @endif
                        </div>

                        <!-- Aggiungi un divisore di riga ogni 36 celle -->
                        @if (($index + 1) % 36 == 0)
                            <div class="w-100"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal per visualizzare i dettagli della fermata -->
    <div class="modal fade" id="stopModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Dettagli della Fermata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Coordinate:</strong> <span id="modalCoordinates"></span></p>
                    <p><strong>Nome Settore:</strong> <span id="modalSectorName"></span></p>
                    <p><strong>Tipo:</strong> <span id="modalType"></span></p>
                    <p><strong>Descrizione:</strong> <span id="modalDescription"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(cell) {
            // Ottieni i dati dalla cella cliccata
            var x = cell.getAttribute('data-x');
            var y = cell.getAttribute('data-y');
            var sector = cell.getAttribute('data-sector');
            var type = cell.getAttribute('data-type');
            var description = cell.getAttribute('data-description');

            // Imposta i dati nel modal
            document.getElementById('modalCoordinates').innerText = x + ', ' + y;
            document.getElementById('modalSectorName').innerText = sector;
            document.getElementById('modalType').innerText = type.charAt(0).toUpperCase() + type.slice(1);
            document.getElementById('modalDescription').innerText = description;

            // Mostra il modal
            var myModal = new bootstrap.Modal(document.getElementById('stopModal'));
            myModal.show();
        }
    </script>
@endsection
