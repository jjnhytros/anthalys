@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.report_content') }}</h1>

    <form action="{{ route('report.store', ['type' => $type, 'id' => $id]) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="reason">{{ __('messages.reason') }}</label>
            <input type="text" class="form-control" id="reason" name="reason" required>
        </div>

        <div class="mb-3">
            <label for="description">{{ __('messages.description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-danger">{{ __('messages.submit_report') }}</button>
    </form>
@endsection
