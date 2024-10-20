@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <h1>Notifiche</h1>

        <!-- Container per le notifiche -->
        <div id="notification-list">
            @if ($notifications->isEmpty())
                <p>Non ci sono notifiche.</p>
            @else
                <ul class="list-group">
                    @foreach ($notifications as $notification)
                        <li
                            class="list-group-item {{ $notification->status == 'unread' ? 'list-group-item-warning' : 'list-group-item-light' }}">
                            <h5>{{ $notification->subject }}</h5>
                            <p>{{ $notification->message }}</p>

                            <!-- Azioni sulle notifiche -->
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Marca come letta</button>
                            </form>

                            <form action="{{ route('notifications.archive', $notification->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">Archivia</button>
                            </form>

                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Elimina</button>
                            </form>

                            @if ($notification->trashed())
                                <form action="{{ route('notifications.restore', $notification->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">Ripristina</button>
                                </form>
                                <form action="{{ route('notifications.forceDelete', $notification->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Elimina definitivamente</button>
                                </form>
                            @endif
                        </li>
                    @endforeach
                </ul>

                <!-- Paginazione -->
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
