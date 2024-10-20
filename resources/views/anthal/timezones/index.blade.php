@extends('layouts.main')

@section('content')
    <div class="container">
        <h1 class="mb-4">{{ __('messages.timezones') }}</h1>

        <!-- Pulsante per creare un nuovo Timezone -->
        <a href="{{ route('timezones.create') }}" class="btn btn-primary mb-3">{{ __('messages.create_new_timezone') }}</a>

        <!-- Tabella per mostrare i Timezones -->
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>{{ __('messages.identifier_abbr') }}</th>
                    <th>{{ __('messages.coordinates_offset') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($timezones as $timezone)
                    <tr>
                        <td>{{ $timezone->identifier }} ({{ $timezone->abbreviation }})</td>
                        <td>
                            {{ __('messages.lat') }}: {{ $timezone->latitude }} | {{ __('messages.long') }}:
                            {{ $timezone->longitude }}<br />
                            {{ __('messages.offset') }}: {{ $timezone->offset_hours }} {{ __('messages.hours') }}
                        </td>
                        <td>
                            <a href="{{ route('timezones.edit', $timezone->id) }}"
                                class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>

                            <!-- Pulsante di cancellazione -->
                            <form action="{{ route('timezones.destroy', $timezone->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                            </form>

                            @if ($timezone->trashed())
                                <!-- Pulsante di ripristino -->
                                <form action="{{ route('timezones.restore', $timezone->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-success">{{ __('messages.restore') }}</button>
                                </form>

                                <!-- Pulsante per la cancellazione permanente -->
                                <form action="{{ route('timezones.forceDelete', $timezone->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-danger">{{ __('messages.permanently_delete') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
