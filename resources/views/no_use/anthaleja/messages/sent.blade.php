@extends('layouts.mail')

@section('title', 'Sent Messages')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('anthaleja.messages.partials.sidebar')
        </div>

        <div class="col-sm-9">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Sent Messages ({{ $messages->total() }})</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($messages as $message)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">To: {{ $message->recipient->username }}</h6>
                                            <p class="mb-0 text-muted">{{ Str::limit($message->body, 50) }}</p>
                                        </td>
                                        <td class="text-end">{{ $message->created_at->format('M d') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('messages.show', $message->id) }}"
                                                class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No sent messages found.</td>
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
