@extends('layouts.main')

@section('content')
    <h1>{{ __('messages.objects_list') }}</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.type') }}</th>
                <th>{{ __('messages.price') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($objects as $object)
                <tr>
                    <td>{{ $object->name }}</td>
                    <td>{{ $object->type }}</td>
                    <td>{{ $object->price }}</td>
                    <td>
                        <form action="{{ route('objects.buy', ['character' => $character->id, 'objekt' => $object->id]) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">{{ __('messages.buy') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
