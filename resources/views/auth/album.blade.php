@extends('layouts.app')

@section('title', 'Album')

@section('content')
<style>
    #album-cover {
        width: 990px;
        border: 1px solid black;
        margin: auto;
        padding: 20px;
        /* text-align: center; */
    }

    .album-item {

        width: 300px;
        height: 300px;
        border: 1px solid black;
        margin: 3px;
        display: inline-block;
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
</style>
<h2>Album</h2>
<div id="album-cover">
    @foreach ($albums as $album)
    @php
    $count = \App\Models\Photo::where('album_id', $album->id)->count()
    @endphp

    <div class="album-item" data-id="{{ $album->id }}">
        <a href="#" class="close-btn">X</a>
        <a href="#" class="plus-hidden">+</a>
        <div class="album-name">{{ $count == 0 ? 'Empty Album' : $album->title }}</div>
    </div>
    @endforeach
    <div class="album-item center-box">
        <a href="#" id="plus" data-toggle="modal" data-target="#exampleModal">+</a>
        <!-- <div class="plus"></div> -->
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Album</h5>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="title" class="col-form-label">Title:</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label for="status" class="col-form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="add-album">Add</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".album-item").click(function() {
            var album_id = $(this).attr("data-id");
            // alert(album_id);
            window.location.href = '/album/manage/' + album_id;
        });

        $("#plus").click(function(e) {
            // $("#plus").attr("data-target", "#exampleModal");
            $("#title").val("");
            $("#status").val("");
            $("#exampleModal").modal("show");
            return false;
        });

        $("#add-album").click(function(e) {
            $("#exampleModal").modal("hide");
            var title = $("#title").val();
            var status = $("#status").val();
            $.post("{{ route('album.store') }}", {
                title: title,
                status: status,
                _token: "{{ csrf_token() }}"
            }, function(response) {
                console.log(response);
                location.reload();
            });
            // $.ajax({
            //     url: "{{ route('album.store') }}",
            //     type: "POST",
            //     data: {
            //         title: title,
            //         status: status,
            //         _token: "{{ csrf_token() }}"
            //     },
            //     success: function(response) {
            //         location.reload();
            //     }
            // });
        });

        $("#exampleModal").on("hidden.bs.modal", function() {
            // put your default event here
            $("#title").val("");
            $("#status").val("");
        });
    });
</script>
@endsection