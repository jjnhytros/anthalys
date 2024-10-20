@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>{{ $email->subject }}</h1>
        <p><strong>Da:</strong> {{ $email->sender->name }} ({{ $email->sender->email }})</p>
        <p><strong>Data:</strong> {{ $email->created_at->format('d-m-Y H:i') }}</p>
        <hr>
        <p>{{ $email->message }}</p>

        @if ($email->attachments)
            <hr>
            <h5>Allegati:</h5>
            <ul>
                @foreach (json_decode($email->attachments) as $index => $attachment)
                    <li>
                        <a
                            href="{{ route('messages.downloadAttachment', ['message' => $email->id, 'attachmentIndex' => $index]) }}">{{ basename($attachment) }}</a>

                        <!-- Controlla se l'allegato è un'immagine -->
                        @if (preg_match('/\.(jpg|jpeg|png)$/i', $attachment))
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $attachment) }}" alt="Allegato Immagine" class="img-fluid"
                                    style="max-width: 200px;">
                            </div>
                        @endif

                        <!-- Form per eliminare l'allegato -->
                        <form
                            action="{{ route('messages.deleteAttachment', ['message' => $email->id, 'attachmentIndex' => $index]) }}"
                            method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">{!! getIcon('trash', 'bi') !!} Elimina</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('messages.emailInbox') }}" class="btn btn-secondary mt-4">Torna alla Posta in Arrivo</a>
    </div>
@endsection
