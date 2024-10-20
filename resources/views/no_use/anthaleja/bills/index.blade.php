@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.bills') }}</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('messages.property') }}</th>
                <th>{{ __('messages.amount') }}</th>
                <th>{{ __('messages.due_date') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bills as $bill)
                <tr>
                    <td>{{ $bill->property->address }}</td>
                    <td>{{ $bill->amount }}</td>
                    <td>{{ $bill->due_date }}</td>
                    <td>{{ $bill->paid ? __('messages.paid') : __('messages.unpaid') }}</td>
                    <td>
                        @if (!$bill->paid)
                            <form action="{{ route('bills.pay', $bill->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">{{ __('messages.pay') }}</button>
                            </form>
                        @else
                            <span class="text-success">{{ __('messages.paid') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
