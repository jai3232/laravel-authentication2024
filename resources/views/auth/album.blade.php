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
</style>
<h2>Album</h2>
<div id="album-cover">
    <?php //$albums = [1, 2, 3, 4] 
    ?>
    @foreach ($albums as $album)
    <div class="album-item">
        <a href="#" class="close-btn">X</a>
        <a href="#" class="plus-hidden">+</a>
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
    });
</script>
@endsection