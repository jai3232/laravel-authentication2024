@extends('layouts.app')

@section('title', 'Gallery')

@push('styles')
<style>
     .gallery {
            display: flex;
            flex-wrap: wrap;
        }
        .thumbnail {
            width: 100px;
            height: 100px;
            margin: 5px;
            object-fit: cover;
        }
        .main-photo {
            width: 500px;
            height: 500px;
            margin-bottom: 20px;
            object-fit: cover;
        }
        .controls {
            display: flex;
            justify-content: space-between;
            width: 520px;
        }
</style>
@endpush

@section('content')

<style>
    #gallery-container {
        text-align: center;
    }

    #main-photo {
        margin-bottom: 20px;
    }

    #main-image {
        width: 80%;
        height: auto;
    }

    #thumbnails {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #thumbnail-container {
        display: flex;
        overflow: hidden;
        width: 60%;
    }

    .thumbnail {
        width: 100px;
        height: 100px;
        margin: 0 5px;
        cursor: pointer;
    }

    button {
        padding: 10px;
        cursor: pointer;
    }

</style>

<h2>Gallery Album {{ $photos[0]->album_id}}</h2>
<!-- <img src="{{ '/thumbnail/'.$photos[0]->url }}"> -->
<div id="gallery-container">
        <div id="main-photo">
            <img id="main-image" src="" alt="Main Photo">
        </div>
        <div id="thumbnails">
            <button id="prev-btn">Previous</button>
            <div id="thumbnail-container"></div>
            <button id="next-btn">Next</button>
        </div>
    </div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).ready(function() {
            const photos = [
            @foreach($photos as $photo)
             {!! '{url: "/thumbnail/'.$photo->url.'", thumbnailUrl: "/thumbnail/'.$photo->url.'"},' !!}
            @endforeach
                // { url: 'images/photo1.jpg', thumbnailUrl: 'images/thumb1.jpg' },
                // { url: 'images/photo2.jpg', thumbnailUrl: 'images/thumb2.jpg' },
                // { url: 'images/photo3.jpg', thumbnailUrl: 'images/thumb3.jpg' },
                // { url: 'images/photo4.jpg', thumbnailUrl: 'images/thumb4.jpg' },
                // { url: 'images/photo5.jpg', thumbnailUrl: 'images/thumb5.jpg' }
            ];
            let currentIndex = 0;

            // Display thumbnails
            function displayThumbnails() {
                $('#thumbnail-container').empty();
                for (let i = 0; i < photos.length; i++) {
                    const thumbnail = $('<img>').attr('src', photos[i].thumbnailUrl).addClass('thumbnail');
                    thumbnail.click(function() {
                        displayMainPhoto(i);
                    });
                    $('#thumbnail-container').append(thumbnail);
                }
            }

            // Display main photo
            function displayMainPhoto(index) {
                $('#main-image').attr('src', photos[index].url);
                currentIndex = index;
            }

            // Previous button click
            $('#prev-btn').click(function() {
                if (currentIndex > 0) {
                    displayMainPhoto(currentIndex - 1);
                }
            });

            // Next button click
            $('#next-btn').click(function() {
                if (currentIndex < photos.length - 1) {
                    displayMainPhoto(currentIndex + 1);
                }
            });

            // Initialize gallery
            displayThumbnails();
            displayMainPhoto(0);
        });

       
    });
</script>


@endsection