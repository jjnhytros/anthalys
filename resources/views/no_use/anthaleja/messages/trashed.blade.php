@extends('layouts.mail')

@section('title', 'Trashed Messages')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('anthaleja.messages.partials.sidebar')
        </div>

        <div class="col-sm-9">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Trashed Messages</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($messages as $message)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $message->sender->username }} <small class="text-muted">to
                                                    {{ $message->recipient->username }}</small></h6>
                                            <p class="mb-0 text-muted">{{ Str::limit($message->body, 50) }}</p>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('messages.restore', $message->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-warning">Restore</button>
                                            </form>
                                            <form action="{{ route('messages.forceDelete', $message->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this message permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete
                                                    Permanently</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">No trashed messages found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
