{{-- resources/views/anthaleja/messages/show.blade.php --}}

@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>{{ $message->subject }}</h1>
        <p><strong>Mittente:</strong> {{ $message->sender->name ?? 'Sistema' }}</p>
        <p><strong>Data:</strong> {{ $message->created_at->format('d-m-Y H:i') }}</p>
        <hr>
        <p>{{ $message->message }}</p>

        <!-- Sezione per visualizzare gli allegati -->
        @if (!empty($message->attachments))
            <div class="attachments my-3">
                <h5>Allegati:</h5>
                <ul>
                    @foreach (json_decode($message->attachments, true) as $index => $attachment)
                        <li>
                            @php
                                $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                            @endphp
                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <!-- Anteprima per immagini -->
                                <img src="{{ asset('storage/' . $attachment) }}" alt="Allegato" style="max-width: 200px;"
                                    class="img-thumbnail my-2">
                            @elseif(in_array($extension, ['pdf', 'docx', 'txt']))
                                <!-- Icona o link per documenti -->
                                <a href="{{ route('messages.downloadAttachment', [$message->id, $index]) }}"
                                    class="btn btn-link">
                                    <i class="fas fa-file-alt"></i> {{ basename($attachment) }}
                                </a>
                            @else
                                <!-- Download per altri tipi di file -->
                                <a href="{{ route('messages.downloadAttachment', [$message->id, $index]) }}"
                                    class="btn btn-link">
                                    <i class="fas fa-download"></i> {{ basename($attachment) }}
                                </a>
                            @endif
                            <!-- Form per eliminare l'allegato -->
                            <form action="{{ route('messages.deleteAttachment', [$message->id, $index]) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger"
                                    onclick="return confirm('Sei sicuro di voler eliminare questo allegato?')">Elimina</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form di risposta rapida -->
        <form action="{{ route('messages.reply', $message) }}" method="POST" class="my-4"
            onsubmit="return validateReplyForm();">
            @csrf
            <div class="form-group">
                <textarea name="reply_message" class="form-control" rows="4" placeholder="Scrivi la tua risposta..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Invia Risposta</button>
        </form>

        <!-- Form per inoltrare il messaggio -->
        <form action="{{ route('messages.forward', $message) }}" method="POST" class="my-4"
            onsubmit="return validateForwardForm();">
            @csrf
            <div class="form-group">
                <label for="recipient">Inoltra a:</label>
                <select name="recipient_id" class="form-control" required>
                    @foreach ($recipients as $recipient)
                        <option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary mt-2">Inoltra Messaggio</button>
        </form>

        <a href="{{ route('messages.inbox') }}" class="btn btn-secondary mt-4">Torna alla Posta in Arrivo</a>
    </div>

    <script></script>
@endsection
