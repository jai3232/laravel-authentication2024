@extends('layouts.app')

@section('title', 'User')

@section('content')

<h2>User</h2>
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Album</th>
            <th>Photos</th>
            <th>Like</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td><a href="#" class="btn btn-warning">Delete</a></td>
        </tr>
        @endforeach

    </tbody>
</table>

@endsection