@extends('layouts.main')
@section('title', 'Register')
@section('content')
    <form action="{{ route('login.submit') }}" method="POST">
        @csrf
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
@endsection
