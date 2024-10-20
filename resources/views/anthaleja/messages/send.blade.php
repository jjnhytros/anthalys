@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>Invia un Messaggio</h1>

        <form action="{{ route('messages.send') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="recipient_id" class="form-label">Destinatario</label>
                <select name="recipient_id" class="form-control" required>
                    <!-- Popola con la lista dei personaggi -->
                </select>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Oggetto</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Messaggio</label>
                <textarea name="message" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Invia</button>
        </form>
    </div>
@endsection
