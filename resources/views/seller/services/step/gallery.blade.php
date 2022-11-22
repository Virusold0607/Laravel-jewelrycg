<style>
    .modal-content{
        overflow: hidden;
    }
    .modal-body{
        overflow: auto;
    }    
    .check-option {
        right: 4px;
        top: 4px;
    }
    #thumbnail .dropzone {
        border-radius: 25px;
        width: 132px;
        overflow: hidden;
        padding: 4px;
        background: transparent;
    }
    #thumbnail .dropzone .dz-preview{
        margin: 0;
    }
    
    .dz-image img{
        width: 100%;
        height: 100%;
    }
</style>
<form action="{{ route('seller.services.gallery') }}" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            @csrf
            <div class="card col-md-12 mb-4">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title mb-0">Service information</h4>
                </div>
                <!-- End Header -->
                <div class="card-body">
                    <input type="hidden" name="service_id" id="service_id" value="{{$post_id}}" >
                    <input type="hidden" name="step" id="name" value="{{$step}}" class="form-control">
                    <input type="hidden" id="thumb" name="thumb" value="{{ null !== old('thumb') ? old('thumb') : (isset($data->thumbnail) ? $data->thumbnail : "") }}">
                    <input type="hidden" id="gallery" name="gallery" value="{{ null !== old('gallery') ? old('gallery') : (isset($data->gallery) ? $data->gallery : "") }}">
                    @include('includes.validation-form')
                    <div class="card mb-3 mb-4">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title mb-0">Thumbnail</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <div id="thumbnail">
                                <div class="dropzone" id="thumbnail_dropzone"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <!-- Header -->
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title mb-0">Gallery</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <div id="gallery_container">
                                <div class="dropzone" id="gallery_dropzone">
                                </div>
                            </div>
                        </div>
                        <!-- Body -->
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row justify-content-center justify-content-sm-between">
        <div class="col">
        <a type="button" class="btn btn-danger" href="{{route('seller.services.list')}}">Cancel</a>
        </div>
        <!-- End Col -->

        <div class="col-auto">
        <div class="d-flex flex-column gap-3">
            <button type="submit" class="btn btn-primary">Save & Continue</button>
            <a type="button" class="btn btn-light" href="{{"/seller/services/create/".($step-1)."/".$post_id}}">Back</a> 
        </div>
        </div>
        <!-- End Col -->
    </div>
    <!-- End Card -->
    </div>
</form>

<div id='ajaxCalls'>
</div>

@section('js')
<script src="{{ asset('dropzone/js/dropzone.js') }}"></script>
<script>
    var galleries = {!! json_encode($data->galleries) !!}
    var thumbnail = {!! json_encode($data->uploads) !!}
    var currentFile = null;
    Dropzone.autoDiscover = false;
    var thumbnailDropzone, galleryDropzone;
    $(document).ready(function() {
        $("#thumbnail_dropzone").dropzone({
            method:'post',
            url: "{{ route('seller.file.thumb') }}",
            dictDefaultMessage: "Select photos",
            paramName: "file",
            maxFilesize: 2,
            maxFiles: 1,
            clickable: true,
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            init: function () {
                thumbnailDropzone = this;

                if(thumbnail.id) {
                    var mockFile = { name: thumbnail.file_original_name, size: thumbnail.file_size };

                    thumbnailDropzone.emit("addedfile", mockFile);
                    thumbnailDropzone.emit("thumbnail", mockFile, `{{asset("/uploads/all")}}/${thumbnail.file_name}`);
                    thumbnailDropzone.emit("complete", mockFile);
                }
            },
            success: (file, response) => {
                var inputs = $("#inputs");
                var last = $("#thumb");

                last.val(response.id)
                thumbnail = response;
                // ONLY DO THIS IF YOU KNOW WHAT YOU'RE DOING!
            },
            removedfile: function(file) {
                $.ajax({
                    url: `/seller/file/destroy/${thumbnail.id}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function(result) {
                        var last = $("#thumb");
                        last.val("")
                        $(file.previewElement).remove();
                    },
                    error: function(error) {
                        return false;
                    }
                });
            }
        })
        $("#gallery_dropzone").dropzone({
            method:'post',
            url: "{{ route('seller.file.image') }}",
            dictDefaultMessage: "Select photos",
            paramName: "file",
            maxFilesize: 2,
            clickable: true,
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            init: function () {
                galleryDropzone = this;
                
                for (const gallery of galleries) {
                    if(!gallery) {
                        continue;
                    }

                    var mockFile = { name: gallery.file_original_name, size: gallery.file_size };

                    galleryDropzone.emit("addedfile", mockFile);
                    galleryDropzone.emit("thumbnail", mockFile, `{{asset("/uploads/all")}}/${gallery.file_name}`);
                    galleryDropzone.emit("complete", mockFile);
                }
            },
            success: (file, response) => {
                var inputs = $("#inputs");
                var last = $("#gallery");
                var lastValue = last.val().split(',');

                lastValue.push(response.id);
                galleries.push(response);
                last.val(lastValue);
                console.log(galleries);
            },
            removedfile: function(file) {
                for(var i=0;i<galleries.length;++i){
                    if(!galleries[i]) {
                        continue;
                    }
                    if(galleries[i].file_original_name + "." + galleries[i].extension==file.name) {
                        $.ajax({
                            url: `/seller/file/destroy/${galleries[i].id}`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            success: function(result) {
                                var last = $("#gallery");
                                var lastValue = last.val().split(',');
                                lastValue.splice(i, 1);
                                last.val(lastValue);
                                $(file.previewElement).remove();
                                galleries.splice(i, 1)
                            },
                            error: function(error) {
                                return false;
                            }
                        });
                        break;
                    }
                }
            }
        })
    });
</script>
@endsection