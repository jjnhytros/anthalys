@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h2>Dettagli Utente</h2>
            <form action="{{ route('register.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
        </div>

        <div class="col-md-6">
            <h2>Dettagli Personaggio</h2>
            <div class="mb-3">
                <label for="first_name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Cognome</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
                <label for="character_username" class="form-label">Nome del Personaggio</label>
                <input type="text" class="form-control" id="character_username" name="character_username" required>
            </div>
            <div class="mb-3">
                <label for="bank_account" class="form-label">Numero di Conto Bancario</label>
                <input type="text" class="form-control" id="bank_account" name="bank_account" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="have_phone" name="have_phone">
                <label class="form-check-label" for="have_phone">Possiede un telefono?</label>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Numero di Telefono (opzionale)</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="999-999 9999">
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Registrati</button>
        </div>
        </form>
    </div>
@endsection
