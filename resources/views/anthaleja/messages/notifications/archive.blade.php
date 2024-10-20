@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>Archivio Notifiche</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Notifica</th>
                    <th>Data</th>
                    <th>Stato</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $notification)
                    <tr
                        class="{{ $notification->trashed() ? 'table-danger' : ($notification->status == 'unread' ? 'table-warning' : '') }}">
                        <td>{{ $notification->subject }}</td>
                        <td>{{ $notification->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $notification->trashed() ? 'Cancellata' : ucfirst($notification->status) }}</td>
                        <td>
                            @if (!$notification->trashed() && $notification->status == 'unread')
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Segna come Letta</button>
                                </form>
                            @endif
                            @if (!$notification->trashed())
                                <form action="{{ route('notifications.delete', $notification->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                                </form>
                            @else
                                <form action="{{ route('notifications.restore', $notification->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm">Recupera</button>
                                </form>
                                <form action="{{ route('notifications.forceDelete', $notification->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Elimina Definitivamente</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
