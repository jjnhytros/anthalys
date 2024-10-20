@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>Messaggi Archiviati</h1>
        <form action="{{ route('messages.restoreArchived') }}" method="POST">
            @csrf
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Mittente</th>
                        <th>Oggetto</th>
                        <th>Data</th>
                        <th>Stato</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($archivedMessages as $message)
                        <tr class="{{ $message->status == 'unread' ? 'table-warning' : '' }}">
                            <td><input type="checkbox" name="messages[]" value="{{ $message->id }}"></td>
                            <td>{{ $message->sender->name }}</td>
                            <td><a href="{{ route('messages.show', $message) }}">{{ $message->subject }}</a></td>
                            <td>{{ $message->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ ucfirst($message->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success mt-2">{!! getIcon('arrow-counterclockwise', 'bi') !!} Ripristina Selezionati</button>
        </form>

        <form action="{{ route('messages.forceDeleteArchived') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger mt-2">{!! getIcon('trash', 'bi') !!} Elimina Definitivamente
                Selezionati</button>
        </form>

        <script></script>
    </div>
@endsection
