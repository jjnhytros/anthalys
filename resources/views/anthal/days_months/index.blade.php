@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>{{ __('messages.days_months_management') }}</h1>
        <hr>
        <div class="row">
            <!-- Days Section -->
            <div class="col-md-6">
                <h2>{{ __('messages.days') }}</h2>
                <form action="{{ route('day_months.updateDays') }}" method="POST" onsubmit="return confirmEdit()"> @csrf
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($days as $day)
                                <tr>
                                    <td>
                                        <input type="text" name="days[{{ $day->id }}]" value="{{ $day->name }}"
                                            class="form-control">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update_days') }}</button>
                </form>
            </div>

            <!-- Months Section -->
            <div class="col-md-6">
                <h2>{{ __('messages.months') }}</h2>
                <form action="{{ route('day_months.updateMonths') }}" method="POST" onsubmit="return confirmEdit()"> @csrf
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('messages.name') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($months as $month)
                                <tr>
                                    <td>
                                        <input type="text" name="months[{{ $month->id }}]"
                                            value="{{ $month->name }}" class="form-control">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update_months') }}</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmEdit() {
            return confirm("{{ __('messages.confirm_edit') }}");
        }
    </script>
@endsection
