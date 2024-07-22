@extends('layouts.app')

@section('title', 'Gallery')

@push('styles')
<style>
  .gallery-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }

  #full-image {
    width: 100%;
    /* margin-bottom: 20px; */
  }

  .image-container {
    position: relative;
    /* Required for absolute positioning of like button */
  }

  .navigation-wrapper {
    display: flex;
    align-items: center;
  }

  .thumbnails-wrapper {
    display: flex;
    overflow-x: scroll;
    flex-grow: 1;
    /* Allow thumbnails to fill remaining space */
  }

  .thumbnails {
    display: flex;
    flex-wrap: wrap;
  }

  .thumbnail {
    width: 150px;
    height: 150px;
    margin: 5px;
    border: 1px solid #ddd;
    cursor: pointer;
  }

  .thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  button {
    padding: 10px;
    border: none;
    background-color: #ddd;
    cursor: pointer;
  }

  #prev-btn[disabled] {
    opacity: 0.5;
    cursor: default;
  }

  .like-container {
    position: absolute;
    bottom: 10px;
    left: 10px;
  }

  .like-container button {
    background-color: transparent;
    border: none;
    cursor: pointer;
    color: black;
  }

  #like-btn.liked {
    color: black;
  }
</style>
@endpush

@section('content')
<div class="gallery-container">
  <div class="image-container">
    <img id="full-image" src="" alt="Full Image">
    <div class="like-container">
      <button id="like-btn"><span style="font-size: 3em;">&hearts;</span>x <span id="like-count">0</span></button>
    </div>
  </div>
  <div class="info-container"></div>
  <div class="navigation-wrapper">
    <button id="prev-btn" disabled>&lt;</button>
    <div class="thumbnails-wrapper">
      <div class="thumbnails"></div>
    </div>
    <button id="next-btn">&gt;</button>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {

    // Define folder path containing images (replace with your actual path)
    const imageFolder = "/thumbnail/";

    // Define image list (replace with actual filenames)
    const imageList = [
      @foreach($photos as $photo) 
      {!! '\''.$photo-> url.'?id='.$photo->id.'\', ' !!}
      @endforeach
      // "image1.jpg",
      // "image2.jpg",
      // "image3.jpg",
      // Add more image filenames here
    ];

    // Variables for pagination
    let currentPage = 0;
    const thumbnailsPerPage = 5;
    updatePhotoInfo(0);
    let isLiked = false; // Variable to track like state

    // Function to display thumbnails based on current page
    function displayThumbnails(page) {
      const startIndex = page * thumbnailsPerPage;
      const endIndex = Math.min(startIndex + thumbnailsPerPage, imageList.length);
      $('.thumbnails').empty(); // Clear existing thumbnails
      for (let i = startIndex; i < endIndex; i++) {
        $('.thumbnails').append(`<div class="thumbnail"><img src="${imageFolder + imageList[i]}" alt="Thumbnail"></div>`);
      }
    }

    // Load initial thumbnails
    displayThumbnails(currentPage);

    // Set initial image
    $('#full-image').attr('src', imageFolder + imageList[currentPage]);
    var id = $('#full-image').attr('src').split('=')[1];
    likeCount(id);

    // Hide next button if there are less than 5 images
    if (imageList.length <= thumbnailsPerPage) {
      $('#next-btn').hide();
    }

    // Click event for thumbnails
    // $('.thumbnail').click(function() {
    //   const index = $(this).index() + currentPage * thumbnailsPerPage;
    //   $('#full-image').attr('src', imageFolder + imageList[index]);
    //   alert("X")
    // });
    $(document).on('click', '.thumbnail', function() {
      const index = $(this).index() + currentPage * thumbnailsPerPage;
      $('#full-image').attr('src', imageFolder + imageList[index]);
      photo_id = $('#full-image').attr('src').split('=')[1];
      updatePhotoInfo(index);
      likeCount(photo_id);
    });

    // Previous button click
    $('#prev-btn').click(function() {
      if (currentPage > 0) {
        currentPage--;
        displayThumbnails(currentPage);
        updateButtons();
      }
    });

    // Next button click
    $('#next-btn').click(function() {
      if (currentPage < Math.ceil(imageList.length / thumbnailsPerPage) - 1) {
        currentPage++;
        displayThumbnails(currentPage);
        updateButtons();
      }
    });

    $('#like-btn').click(function() {
      isLiked = !isLiked; // Toggle like state on click
      $(this).toggleClass('liked'); // Toggle button color
      photo_id = $('#full-image').attr('src').split('=')[1];
      $.post('/photo/like', { photo_id: photo_id, is_liked: isLiked }, function(data) {
        console.log(data);
        likeCount(photo_id);
      });
      
    });

    // Function to update button states based on current page
    function updateButtons() {
      if (currentPage === 0) {
        $('#prev-btn').prop('disabled', true);
      } else {
        $('#prev-btn').prop('disabled', false);
      }

      if (currentPage === Math.ceil(imageList.length / thumbnailsPerPage) - 1) {
        $('#next-btn').prop('disabled', true);
      } else {
        $('#next-btn').prop('disabled', false);
      }
    }

    // Function to update photo information
    function updatePhotoInfo(index) {
      const photoInfo = `Photo ${index + 1} of ${imageList.length}`; // Customize information
      $('.info-container').text(photoInfo);
    }

    function likeCount(photo_id) {
      $.get('/photo/like', { photo_id: photo_id }, function(data) {
        console.log(data);
        $("#like-count").html(data);
        if(data/1 > 0)
          $("#like-btn").css("color", "red");
        else
          $("#like-btn").css("color", "black");
        return data;
      });
    }

    // function isLiked(photo_id) {
    //   $.get('/photo/isliked', { photo_id: photo_id }, function(data) {
      
    //   }
    // }

  });
</script>


@endsection