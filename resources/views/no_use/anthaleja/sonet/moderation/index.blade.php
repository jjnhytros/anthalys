@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.moderation_dashboard') }}</h1>

    @foreach ($reports as $report)
        <div class="card mb-3">
            <div class="card-body">
                <p>{{ __('messages.reported_by') }}: {{ $report->user->name }}</p>
                <p>{{ __('messages.reason') }}: {{ $report->reason }}</p>
                <p>{{ __('messages.description') }}: {{ $report->description }}</p>
                <form action="{{ route('moderation.resolve', $report->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">{{ __('messages.resolve_report') }}</button>
                </form>
            </div>
        </div>
    @endforeach
@endsection
