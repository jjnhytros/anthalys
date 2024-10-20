@extends('layouts.admin')

@section('content')
    <h1>AI Recommendations</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suggestions as $suggestion)
                <tr>
                    <td>{{ $suggestion->title }}</td>
                    <td>{{ Str::limit($suggestion->content, 100) }}</td>
                    <td>
                        <a href="{{ route('ai_recommendations.approve', $suggestion->id) }}"
                            class="btn btn-success">Approve</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
