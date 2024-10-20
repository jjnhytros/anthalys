@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Benvenuto nella Dashboard</h2>
        <p>Qui potrai gestire tutte le funzionalità amministrative del sito.</p>

        <!-- Puoi aggiungere grafici, statistiche o altre informazioni qui -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Statistiche utente</h5>
                    </div>
                    <div class="card-body">
                        <p>Totale utenti: 100</p>
                        <p>Utenti attivi: 80</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Ordini recenti</h5>
                    </div>
                    <div class="card-body">
                        <p>Ordini completati: 45</p>
                        <p>Ordini in attesa: 5</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
