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
    <div class="main-photo-container">
        <img id="main-photo" class="main-photo" src="" alt="Main Photo">
    </div>
    <div class="controls">
        <button id="prev-btn">Previous</button>
        <button id="next-btn">Next</button>
    </div>
    <div class="gallery" id="gallery"></div>

    <script>
        const photos = [
            @foreach($photos as $photo)
            {!! '"/thumbnail/'.$photo->url.'",' !!}
            @endforeach
            // 'photo1.jpg', 'photo2.jpg', 'photo3.jpg', 'photo4.jpg', 'photo5.jpg',
            // 'photo6.jpg', 'photo7.jpg', 'photo8.jpg', 'photo9.jpg', 'photo10.jpg'
        ];
        let currentIndex = 0;
        const thumbnailsPerPage = 5;

        function updateGallery() {
            const gallery = document.getElementById('gallery');
            gallery.innerHTML = '';
            const start = currentIndex;
            const end = Math.min(start + thumbnailsPerPage, photos.length);
            for (let i = start; i < end; i++) {
                const img = document.createElement('img');
                img.src = photos[i];
                img.className = 'thumbnail';
                img.onclick = () => showPhoto(i);
                gallery.appendChild(img);
            }
        }

        function showPhoto(index) {
            const mainPhoto = document.getElementById('main-photo');
            mainPhoto.src = photos[index];
        }

        document.getElementById('prev-btn').onclick = () => {
            if (currentIndex > 0) {
                currentIndex -= thumbnailsPerPage;
                updateGallery();
            }
        };

        document.getElementById('next-btn').onclick = () => {
            if (currentIndex + thumbnailsPerPage < photos.length) {
                currentIndex += thumbnailsPerPage;
                updateGallery();
            }
        };

        // Initialize gallery
        updateGallery();
        showPhoto(0);
    </script>


@endsection