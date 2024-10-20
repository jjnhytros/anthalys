@extends('layouts.mail')

@section('title', 'Compose Email')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('anthaleja.messages.partials.sidebar')
        </div>

        <div class="col-sm-9">
            <div class="card shadow">
                <div class="card-header">
                    <h3 class="card-title mb-0">Compose Email</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient_id" class="form-label">To</label>
                            <select class="form-select" id="recipient_id" name="recipient_id" required>
                                <option value="">Select Recipient</option>
                                @foreach ($characters as $character)
                                    <option value="{{ $character->id }}">{{ $character->username }}</option>
                                @endforeach
                            </select>
                            @error('recipient_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject"
                                value="{{ old('subject') }}">
                            @error('subject')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Message</label>
                            <textarea class="form-control" id="body" name="body" rows="5" required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
