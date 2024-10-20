@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>{{ __('messages.provinces_list') }}</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <a
                            href="{{ route('provinces.index', ['sort_by' => 'province', 'order' => $sortColumn === 'province' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                            {{ __('messages.province') }}
                            @if ($sortColumn === 'province')
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a
                            href="{{ route('provinces.index', ['sort_by' => 'full_name', 'order' => $sortColumn === 'full_name' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                            {{ __('messages.full_name') }}
                            @if ($sortColumn === 'full_name')
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a
                            href="{{ route('provinces.index', ['sort_by' => 'state', 'order' => $sortColumn === 'state' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                            {{ __('messages.state') }}
                            @if ($sortColumn === 'state')
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a
                            href="{{ route('provinces.index', ['sort_by' => 'area_km2', 'order' => $sortColumn === 'area_km2' && $sortDirection === 'asc' ? 'desc' : 'asc']) }}">
                            {{ __('messages.area_km2') }}
                            @if ($sortColumn === 'area_km2')
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($provinces as $province)
                    <tr>
                        <td>{{ $province['province'] }}</td>
                        <td>{{ $province['full_name'] }}</td>
                        <td>{{ $province['state'] }}</td>
                        <td>{{ number_format($province['area_km2']) }}</td>
                        <!-- Altre colonne -->
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
