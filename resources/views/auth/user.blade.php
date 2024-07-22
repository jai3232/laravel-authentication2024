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
            <th>Likes</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            @php 
            $albums = \App\Models\Album::where('user_id', $user->id);
            $totalPhotos = 0;
            $totalLikes = 0;
            foreach($albums->get() as $album) {
                $totalPhotos += $album->photos->count();
                $photos = \App\Models\Photo::where('album_id', $album->id);
                foreach($photos->get() as $photo) {
                    $totalLikes += $photo->likes->count();
                }
            }
            @endphp
            <td>{{ $albums->count() }}</td>
            <td>{{ $totalPhotos }}</td>
            <td>{{ $totalLikes }}</td>
            <td><a href="#" class="btn btn-warning">Delete</a></td>
        </tr>
        @endforeach

    </tbody>
</table>

@endsection