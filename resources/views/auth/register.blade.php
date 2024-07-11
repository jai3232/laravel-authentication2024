@extends('layouts.app')

@section('title', 'Home')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required class="form-control">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required class="form-control">
    </div>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required class="form-control">
    </div>
    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Register</button>
    </div>
</form>

@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
@if (Session('error'))
<p class="text-danger">{{ session('error') }}</p>
@endif
@endsection