@extends('layouts.main')

@section('content')
    <style>
        .map-container {
            width: 100%;
            height: 80vh;
            position: relative;
        }

        .legend {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: white;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .line-name {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .line-color {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
    </style>

    <div class="container-fluid mb-3">
        <div class="map-container">
            <svg id="transportMap" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 100%; height: 100%;">
                <!-- Le linee di trasporto e le fermate saranno disegnate qui -->
            </svg>

            <div class="legend">
                <h5>Legenda</h5>
                <div id="legendContainer"></div>
            </div>
        </div>
    </div>

    <script>
        const busData = @json($busData);
        const metroData = @json($metroData);

        // Funzione per disegnare la mappa usando SVG
        function drawTransportMap() {
            const svg = document.getElementById('transportMap');
            const legendContainer = document.getElementById('legendContainer');

            // Disegna linee e fermate per i bus
            busData.forEach(line => {
                drawLine(line, svg, legendContainer);
            });

            // Disegna linee e fermate per le metro
            metroData.forEach(line => {
                drawLine(line, svg, legendContainer);
            });
        }

        // Funzione per disegnare una linea (bus o metro)
        function drawLine(line, svg, legendContainer) {
            const path = document.createElementNS("http://www.w3.org/2000/svg", "polyline");
            path.setAttribute("fill", "none");
            path.setAttribute("stroke", line.color);
            path.setAttribute("stroke-width", "0.5");

            const points = line.stops.map(stop => `${stop.x},${stop.y}`).join(' ');
            path.setAttribute("points", points);
            svg.appendChild(path);

            // Disegna le fermate
            line.stops.forEach(stop => {
                const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                circle.setAttribute("cx", stop.x);
                circle.setAttribute("cy", stop.y);
                circle.setAttribute("r", "1");
                circle.setAttribute("fill", line.color);
                svg.appendChild(circle);
            });

            // Aggiungi la linea alla legenda
            const lineElement = document.createElement("div");
            lineElement.classList.add("line-name");
            lineElement.innerHTML = `<div class="line-color" style="background-color: ${line.color}"></div>${line.name}`;
            legendContainer.appendChild(lineElement);
        }

        // Inizializza la mappa al caricamento della pagina
        document.addEventListener('DOMContentLoaded', drawTransportMap);
    </script>
@endsection
