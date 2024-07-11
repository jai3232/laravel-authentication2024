@extends('layouts.app')

@section('title', 'Home')

@section('content')

@if (\Session::has('success'))
<div class="alert alert-info">{{ \Session::get('success') }}</div>
@endif
@if (\Session::has('error'))
<div class="alert alert-info">{{ \Session::get('error') }}</div>
@endif
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required class="form-control">
    </div>
    <div class="form-group">
        <button type="submit" class="form-control btn btn-primary">Login</button>
    </div>
    <div class="form-group">
        <a href="{{ route('register') }}" class="form-control btn btn-secondary">Register</a>
    </div>
</form>

@endsection