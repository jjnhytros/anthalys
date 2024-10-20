@extends('sonet.chat.participants')

@section('chat-content')
    <!-- Sezione dei messaggi della chat -->
    <div class="chat-box" style="height: 400px; overflow-y: scroll;">
        <ul class="list-unstyled" id="message-list">
            @foreach ($messages as $message)
                <li class="mb-3">
                    <strong>{{ $message->sender->username }}</strong>:
                    {{ $message->message }}
                    <br>
                    <small>{{ $message->created_at->diffForHumans() }}</small>

                    @if ($message->sender_id == Auth::user()->character->id)
                        <!-- Opzione per modificare il messaggio -->
                        <form action="{{ route('message.update', [$room->id, $message->id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="text" name="message" value="{{ $message->message }}"
                                class="form-control d-inline-block" style="width: auto;">
                            <button type="submit" class="btn btn-sm btn-primary">Modifica</button>
                        </form>

                        <!-- Opzione per eliminare il messaggio -->
                        <form action="{{ route('message.destroy', [$room->id, $message->id]) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Form per inviare un nuovo messaggio -->
    <form action="{{ route('sonet.chat.store', $room->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea name="message" class="form-control" rows="3" placeholder="Scrivi il tuo messaggio..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Invia</button>
    </form>
@endsection
