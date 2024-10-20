@extends('layouts.main')

@section('content')
    <style>
        .cell {
            width: 2.5vw;
            height: 2.5vw;
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

        .building {
            font-size: 0.7rem;
            color: black;
            padding: 2px;
        }

        .main-building {
            font-weight: bold;
        }

        .subcell-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* Definisce una griglia di 3 colonne */
            grid-gap: 5px;
            /* Spaziatura tra le sottocelle */
        }

        .subcell {
            background-color: #e9ecef;
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .subcell.type-residential {
            background-color: #a2d5c6;
        }

        .subcell.type-commercial {
            background-color: #f6d6ad;
        }

        .subcell.type-road {
            background-color: #b5b5b5;
        }

        .subcell.type-park {
            background-color: #c1e1c5;
        }
    </style>

    <div class="container-fluid mb-3">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="text-center mb-4">Mappa della Città di Anthalys</h1>
                <!-- Pulsante per visualizzare la mappa delle fermate -->
                <a href="{{ route('transport.map') }}" class="btn btn-primary mb-3">Visualizza la Mappa delle Fermate</a>

                <div class="d-flex flex-wrap justify-content-center">
                    @foreach ($squares as $index => $square)
                        <!-- Cella della griglia -->
                        <div class="cell m-0 border border-dark text-center {{ $square['class'] }}"
                            style="width: 2.5vw; height: 2.5vw;" data-id="{{ $square['id'] }}"
                            data-x="{{ $square['x_coordinate'] }}" data-y="{{ $square['y_coordinate'] }}"
                            data-sector="{{ $square['sector_name'] }}" data-type="{{ $square['type'] }}"
                            data-description="{{ $square['description'] }}"
                            data-buildings="{{ json_encode($square['buildings']) }}"
                            data-bus-stop="{{ $square['bus_stop'] }}" data-metro-stop="{{ $square['metro_stop'] }}">

                            <!-- Mostra la lettera a seconda del tipo -->
                            @if ($square['type'] == 'residential')
                                R
                            @elseif($square['type'] == 'commercial')
                                C
                            @elseif($square['type'] == 'industrial')
                                I
                            @elseif($square['type'] == 'police_station')
                                P
                            @elseif($square['type'] == 'fire_station')
                                F
                            @elseif($square['type'] == 'hospital')
                                H
                            @else
                                ?
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

    <!-- Modal interattivo -->
    <div class="modal fade" id="squareModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Dettagli della Cella</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Coordinate:</strong> <span id="modalCoordinates"></span></p>
                    <p><strong>Nome Settore:</strong> <span id="modalSectorName"></span></p>
                    <p><strong>Tipo:</strong> <span id="modalType"></span></p>
                    <p><strong>Descrizione:</strong> <span id="modalDescription"></span></p>

                    <h6>Edifici:</h6>
                    <ul id="buildingList"></ul>

                    <h6>Sottocelle:</h6>
                    <div id="subCellGrid" class="subcell-grid"></div>

                    <p id="busStop" class="d-none"><strong>Fermata Bus:</strong> Sì</p>
                    <p id="metroStop" class="d-none"><strong>Fermata Metro:</strong> Sì</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Cattura il clic sulla cella
            $('.cell').on('click', function() {
                // Ottieni i dati dalla cella cliccata
                var x = $(this).data('x');
                var y = $(this).data('y');
                var sector = $(this).data('sector');
                var type = $(this).data('type');
                var description = $(this).data('description');
                var busStop = $(this).data('bus-stop');
                var metroStop = $(this).data('metro-stop');
                var mapSquareId = $(this).data('id');

                // Imposta i dati nel modal
                $('#modalCoordinates').text(x + ', ' + y);
                $('#modalSectorName').text(sector);
                $('#modalType').text(type.charAt(0).toUpperCase() + type.slice(1));
                $('#modalDescription').text(description);

                // Svuota la lista e la griglia per ricaricarle
                $('#buildingList').empty();
                $('#subCellGrid').empty();

                // Carica le subcells dinamicamente tramite AJAX
                $.ajax({
                    url: '/map/sub-cells/' + mapSquareId,
                    method: 'GET',
                    success: function(response) {
                        var subCells = response.sub_cells;

                        // Se ci sono sottocelle, visualizzale in una griglia
                        if (subCells.length > 0) {
                            subCells.forEach(function(subCell) {
                                var cellHtml = '<div class="subcell type-' + subCell
                                    .type + '">' + subCell.type + '</div>';
                                $('#subCellGrid').append(cellHtml);
                            });
                        } else {
                            $('#subCellGrid').append('<p>Nessuna sottocella</p>');
                        }
                    },
                    error: function() {
                        $('#subCellGrid').append(
                            '<p>Errore nel caricamento delle sottocelle.</p>');
                    }
                });

                // Mostra o nascondi i dettagli delle fermate
                if (busStop) {
                    $('#busStop').removeClass('d-none');
                } else {
                    $('#busStop').addClass('d-none');
                }

                if (metroStop) {
                    $('#metroStop').removeClass('d-none');
                } else {
                    $('#metroStop').addClass('d-none');
                }

                // Mostra il modal
                $('#squareModal').modal('show');
            });
        });
    </script>
@endsection
