@extends('layouts.app')

@section('title', 'Album')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<style>
    #album-cover, #upper-album {
        width: 990px;
        border: 1px solid black;
        margin: auto;
        padding: 20px;
        overflow: auto;
    }

    .photo-item {

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
        background-color: rgba(0, 0, 0, 0.3);
        align-self: flex-end;
        position: relative;
        bottom: -25px;
        width: 100%;
        color: black;
        text-align: center;
        vertical-align: middle;
        line-height: 50px;
    }
    .progress {
        width: 100%;
        background-color: #f3f3f3;
        border: 1px solid #ddd;
        margin-top: 10px;
        position: relative;
    }

    .progress-bar {
        height: 20px;
        background-color: #76c7c0;
        width: 0%;
    }

    .drop-area {
        border: 2px dashed #ccc;
        padding: 20px;
        width: 300px;
        margin: 0 auto;
    }
</style>
<h2>Manage Album</h2>
<div id="upper-album">
<form>
    <div class="form-group">
        <label for="title" class="col-form-label">Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ $album->title}}">
    </div>
    <div class="form-group">
        <label for="status" class="col-form-label">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="public" {{ $album->status == "public" ? "selected" : "" }}>Public</option>
            <option value="private" {{ $album->status == "private" ? "selected" : "" }}>Private</option>
        </select>
    </div>
    <div class="">
        <button type="button" class="btn btn-primary" id="update-album" data-id="{{ $album->id}}">Update</button>    
        <button type="reset" class="btn btn-secondary" id="reset-btn" data-dismiss="modal">Reset</button>
    </div>
</form>
</div>
<div id="album-cover">
    @foreach($photos as $photo)
    <div class="photo-item" data-id="{{$photo->id}}" style="background-image: url(/thumbnail/{{$photo->url}}); background-repeat: no-repeat; background-size: cover;">
        <a href="#" class="close-btn">X</a>
        <a href="#" class="plus-hidden">+</a>
        <div class="album-name">{{$photo->url}}</div>
    </div>
    @endforeach
    <div class="photo-item center-box drop-area">
        <h3>Drop Here</h3>
        <input type="file" name="file" id="fileUpload" accept="image/*" multiple/>
        <a href="#" id="plus" data-toggle="modal" data-target="#exampleModal">+</a>
        <br>
        <progress id="progressBar" value="0" max="100"></progress>
        <!-- <div id="progressContainer"></div> -->
        <!-- <div id="progressBar" style="width: 100%; background-color: #f3f3f3;">
            <div id="progress" style="width: 0%; height: 20px; background-color: #4caf50;"></div>
        </div> -->
        <!-- <div class="plus"></div> -->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // $(".album-item").click(function() {
        //     var album_id = $(this).attr("data-id");
        //     // alert(album_id);
        //     window.location.href = '/album/manage/' + album_id;
        // });
        var ori_title = $("#title").val();
        var ori_status = $("#status").val();
        // console.log("T:" + ori_title + " S:" + ori_status);
        $("#fileUpload").hide();
        $("#fileUpload").change(function(e){
            var files = $(this).prop("files");
            uploadFile(files);
        });

        $("html").on("dragover", function(e){
            e.preventDefault();
            e.stopPropagation();
        });

        $("html").on("drop", function(e){
            e.preventDefault();
            e.stopPropagation();
        });

        $(".drop-area").on("dragenter", function(e){
            e.preventDefault();
            e.stopPropagation();
        });

        $(".drop-area").on("dragover", function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).css("background-color", "red");
        });

        $(".drop-area").on("dragleave", function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).css("background-color", "");
        });

        $(".drop-area").on("drop", function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).css("background-color", "");
            var files = e.originalEvent.dataTransfer.files;
            uploadFile(files);

        });

        function uploadFile(files) {
            // var files = $("#fileUpload").prop("files");
            console.log(files[0].name);
            // return;
            var totalSize = 0;

            for (var i = 0; i < files.length; i++) {
                totalSize += files[i].size;
            }

            var progressBar = $("#progressBar");
            progressBar.val(0);

            var formData = new FormData();

            for (var i = 0; i < files.length; i++) {
                formData.append("files[]", files[i]);
                formData.append('album_id', {{$album->id}});
            }

            $.ajax({
                url: "{{ route('photo.store')}}",
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            var progress = Math.round(percentComplete * 100);
                            progressBar.val(progress);
                        }
                    });
                    return xhr;
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                // Handle successful upload response (data)
                // alert("Files uploaded successfully!");
                console.log(data);
                progressBar.val(100); // Reset progress bar
                location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                // Handle upload error
                // alert("Error uploading files!");
                // console.log(data);
                }
            });
        }
        
            

            // SINGLE UPLOAD FILE //
            // var file = e.target.files[0];
            // var formData = new FormData();
            // formData.append('file', file);
            // formData.append('album_id', {{$album->id}})
            // $.ajax({
            //     url: "{{ route('photo.store')}}",
            //     type: 'POST',
            //     data: formData,
            //     contentType: false,
            //     processData: false,
            //     xhr: function(){
            //         var xhr = new XMLHttpRequest();
            //         xhr.upload.addEventListener('progress', function(e) {
            //             if (e.lengthComputable) {
            //                 var percentComplete = (e.loaded / e.total) * 100;
            //                 $('#progress').css('width', percentComplete + '%');
            //             }
            //         }, false);
            //         return xhr;
            //     },
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     success: function(data) {
            //         console.log(data);
            //         location.reload();

            //     },
            //     error: function(data) {
            //         console.log(data);
            //     }
            // });
        // });

        function uploadFile2(file) {
            var formData = new FormData();
            formData.append('file', file);

            var progressBar = $('<div class="progress"><div class="progress-bar"></div></div>');
            $('#progressContainer').append(progressBar);

            $.ajax({
                url: "{{ route('photo.store')}}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            progressBar.find('.progress-bar').width(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log('File successfully uploaded', response);
                },
                error: function () {
                    console.error('File upload failed.', response);
                }
            });
        }

        $(".close-btn").on("click", function(e){
            if(confirm("Delete this photo?")) {
                var photo_id = $(this).parent().attr("data-id");
                $.post("{{ route('photo.delete') }}", {
                    id: photo_id,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    console.log(response);
                    location.reload();
                });
            }
            e.preventDefault();
        });

        $("#plus").click(function(e) {
            $("#fileUpload").trigger("click");
            // $("#plus").attr("data-target", "#exampleModal");
            // $("#title").val("");
            // $("#status").val("");
            return false;
        });

        $("#reset-btn").click(function(){
            $("#title").val(ori_title);
            $("#status").val(ori_status);
        });

        $("#update-album").click(function(e) {
            var title = $("#title").val();
            var status = $("#status").val();
            var album_id = $(this).attr("data-id");
            $.post("{{ route('album.manage') }}", {
                title: title,
                status: status,
                id: album_id,
                _token: "{{ csrf_token() }}"
            }, function(response) {
                console.log(response);
                // location.reload();
            });
    
        });

    });
</script>
@endsection