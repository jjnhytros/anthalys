@extends('layouts.main')

@section('content')
    <h1>Activities for {{ $character->name }}</h1>

    <h2>Plan a new activity</h2>
    <form action="{{ route('activities.plan', $character->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Activity Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="datetime-local" name="start_time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Plan Activity</button>
    </form>

    <h2>Planned Activities</h2>
    <ul class="list-group">
        @foreach ($activities as $activity)
            <li class="list-group-item">
                <strong>{{ $activity->name }}</strong> - Status: {{ $activity->status }}
                @if ($activity->status === 'pending')
                    <form action="{{ route('activities.execute', $activity->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Execute</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
    <h2>Activity Notifications</h2>
    <ul class="list-group">
        @foreach ($character->notifications as $notification)
            <li class="list-group-item">
                <strong>{{ $notification->title }}</strong>
                <p>{{ $notification->message }}</p>
            </li>
        @endforeach
    </ul>
@endsection
