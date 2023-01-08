    <form action="{{ route('seller.services.store') }}" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-6 col-lg-8 col-md-8 mx-auto">
                @csrf
                <div class="card col-md-12 mb-4">
                    <div class="card-body">
                        <input type="hidden" name="step" id="name" value="{{$step}}" class="form-control">
                        <input type="hidden" name="service_id" id="service_id" value="{{$post_id}}" >
                        @include('includes.validation-form')
                        <div class="mb-4">
                            <label for="name" class="w-100 mb-2 fw-700">Service title</label>
                            <input type="text" name="name" id="name" value="{{ null !== old('name') ? old('name') : (isset($data->name) ? $data->name : "") }}" class="form-control">
                        </div>
                        <div class="mb-4">
                            <label for="category" class="w-100 mb-2 fw-700">Category</label>
                            <p>Select the appropriate category for your service.</p>
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
                            <label for="desc" class="w-100 mb-2 fw-700">Description</label>
                            <p>Provide a detailed description of your service.</p>
                            <textarea name="content" id="desc" rows="6" class="form-control">{{ null !== old('content') ? old('content') : (isset($data->content) ? $data->content : "") }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="w-100 mb-2 fw-700">Tags</label>
                            <p>Include relevant keywords in the tags for your Service to help it be more easily discovered by potential customers.</p>
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
            <a class="btn btn-danger" href="{{route('seller.services.list')}}">Cancel</a>
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
