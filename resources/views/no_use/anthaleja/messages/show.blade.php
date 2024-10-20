@extends('layouts.mail')

@section('title', 'View Message')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('anthaleja.messages.partials.sidebar')
        </div>

        <div class="col-sm-9">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">View Message</h3>
                    <form action="{{ route('messages.destroy', $message->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this message?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                    </form>
                </div>
                <div class="card-body">
                    <h5><strong>From:</strong> {{ $message->sender->username }}</h5>
                    <h5><strong>To:</strong> {{ $message->recipient->username }}</h5>
                    <h5><strong>Subject:</strong> {{ $message->subject }}</h5>
                    <hr>
                    <p>{{ $message->body }}</p>
                    <hr>
                    <p><small class="text-muted">Sent on {{ $message->created_at->format('M d, Y h:i A') }}</small></p>
                </div>
            </div>
        </div>
    </div>
@endsection
