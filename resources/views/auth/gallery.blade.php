@extends('layouts.app')

@section('title', 'Gallery')

@section('content')

<style>
    #album-cover {
        width: 990px;
        border: 1px solid black;
        margin: auto;
        padding: 20px;
        overflow: auto;
    }

    .album-item {

        width: 300px;
        height: 300px;
        border: 1px solid black;
        margin: 3px;
        display: inline-block;
        float: left;
        /* display: flexbox; */
        /* flex-wrap: wrap; */
    }

    .close-btn {
        float: right;
        cursor: pointer;
        font-weight: bold;
        line-height: 2px;
        margin: 10px 5px;

    }

    .close-btn:hover {
        text-decoration: none;
    }

    #plus {
        font-size: 150px;
        display: block;
    }

    #plus:hover {
        text-decoration: none;
    }

    .plus-hidden {
        font-size: 150px;
        display: block;
        visibility: hidden;
    }

    .center-box {
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .album-name {
        height: 50px;
        background-color: rgba(200, 200, 200, 0.8);
        align-self: flex-end;
        position: relative;
        bottom: -25px;
        width: 100%;
        color: black;
        text-align: center;
        vertical-align: middle;
        line-height: 50px;
    }
</style>

<h2>Gallery</h2>

<div id="album-cover">
    @foreach ($albums as $album)
    @php
    $photo = \App\Models\Photo::where('album_id', $album->id);
    $count = $photo->count();
    $firstPhoto = '';
    $bgImage = '';
    if($count > 0) {
        $firstPhoto = $photo->first()->url;
        $bgImage = 'background-image: url(thumbnail/'.$firstPhoto.')';
    }
    @endphp
    @if($count > 0)
    <div class="album-item" data-id="{{ $album->id }}" style="{{$bgImage}}; background-repeat: no-repeat; background-size: cover;">
        <!-- <a href="#" class="close-btn">X</a> -->
        <a href="#" class="plus-hidden">+</a>
        <div class="album-name">{{ $count == 0 ? 'Empty Album' : $album->title }}</div>
    </div>
    @endif
    @endforeach
    <!-- <div class="album-item center-box">
        <a href="#" id="plus" data-toggle="modal" data-target="#exampleModal">+</a>
        <!-- <div class="plus"></div> -->
    </div> -->
</div>


@endsection