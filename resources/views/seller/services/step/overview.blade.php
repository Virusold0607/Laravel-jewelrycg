    <form action="{{ route('seller.services.store') }}" method="post" enctype="multipart/form-data">
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
                        <input type="hidden" name="step" id="name" value="{{$step}}" class="form-control">
                        <input type="hidden" name="service_id" id="service_id" value="{{$post_id}}" >
                        @include('includes.validation-form')
                        <div class="mb-2">
                            <label for="name" class="w-100 mb-2">Name:</label>
                            <input type="text" name="name" id="name" value="{{ null !== old('name') ? old('name') : (isset($data->name) ? $data->name : "") }}" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="desc" class="w-100 mb-2">Service:</label>
                            <textarea name="content" id="desc" rows="6" class="form-control">{{ null !== old('content') ? old('content') : (isset($data->content) ? $data->content : "") }}</textarea>
                        </div>
                        <!-- <div class="mb-4 col-12">
                            <div class="col-12">
                                <label class="mb-2" for="">Status</label>
                                <select class="selectpicker w-100" name="status">
                                    <option value="1" selected>Published</option>
                                    <option value="2" >Draft</option>
                                    <option value="3" >Pending Review</option>
                                </select>
                            </div>
                        </div> -->

                        <div class="mb-4 col-12">
                            <label for="category" class="w-100 mb-2">Category</label>
                            <div class="col-4">
                                <select class="selectpicker form-control" name="categories[]" data-live-search="true" data-container="body">
                                    @foreach ($categories as $category)
                                        <option 
                                            value="{{$category->id}}" 
                                            data-tokens="{{$category->category_name}}" 
                                            {{ isset($data->categories) ? (count($data->categories) ? ($data->categories[0]->id_category === $category->id ? "selected" : ""): "") : "" }}
                                        >{{$category->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="w-100 mb-2">Tags:</label>
                            <select  name="tags[]" id="tags" value="" class="form-control select2"  multiple="multiple" style="width: 100%;">
                                @foreach ($tags as $tag)
                                    <option value='{{ $tag->id }}' {{ isset($data->tag_ids) ? (count($data->tag_ids) ? (in_array($tag->id, $data->tag_ids) ? "selected" : "" ): "" ) : "" }}> {{ $tag->name }} </option>
                                @endforeach
                            </select>
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
            <div class="d-flex gap-3">
                <!-- <button type="button" class="btn btn-light">Save Draft</button> -->
                <button type="submit" class="btn btn-primary">Save & Continue</button>
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
    <script>
        var createChecks = [];

        $('#desc').trumbowyg();

        function removepreviewappended(id) {
            createChecks = jQuery.grep(createChecks, function(value) {
                return value != id;
            });
            $('#fileappend-' + id).remove();
            $('#all_checks').val(createChecks);
        }

        $('.select2').select2({
            tags: true,
            maximumSelectionLength: 10,
            tokenSeparators: [','],
            placeholder: "Select or type keywords",
        })

        function selectFileFromManagerMultiple(id, preview) {
            if ($('#file-' + id).hasClass('selected')) {
                $('#file-' + id).removeClass('selected')
                $('#file-' + id).find('.check-this').fadeOut()
                removepreviewappended(id);
            } else {
                $('#file-' + id).addClass('selected')
                $('#file-' + id).find('.check-this').fadeIn()
                createChecks.push(id)
                $('#fancyboxGallery').prepend(productImageDiv(id, preview))
            }
            $('#all_checks').val(createChecks);
        }
    </script>
@endsection