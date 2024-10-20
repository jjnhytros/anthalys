@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 class="mb-4">Monitoraggio Domanda e Offerta</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome Oggetto</th>
                    <th>Tipo</th>
                    <th>Prezzo Attuale</th>
                    <th>Domanda</th>
                    <th>Variazione Prezzo</th>
                    <th>Motivo della Variazione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ ucfirst($item->type) }}</td>
                        <td>{{ number_format($item->price, 2) }} Athel</td>
                        <td>{{ $item->demand }}</td>
                        <td>
                            @if ($item->price_change > 0)
                                <span class="text-success">+{{ number_format($item->price_change, 2) }}%</span>
                            @elseif ($item->price_change < 0)
                                <span class="text-danger">{{ number_format($item->price_change, 2) }}%</span>
                            @else
                                <span class="text-muted">Nessuna variazione</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->reason)
                                {{ $item->reason }}
                            @else
                                <span class="text-muted">Motivo non disponibile</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
