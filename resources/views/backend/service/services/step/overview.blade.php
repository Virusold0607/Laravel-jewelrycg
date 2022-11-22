    <form action="{{ route('backend.services.store') }}" method="post" enctype="multipart/form-data">
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
                        @include('includes.validation-form')
                        <div class="mb-2">
                            <label for="name" class="w-100 mb-2">Name:</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label for="desc" class="w-100 mb-2">Service:</label>
                            <textarea name="service" id="desc" rows="6" class="form-control">{{ old('service') }}</textarea>
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
                            <div class="col-12">
                                <select class="selectpicker " name="categories[]" data-live-search="true" data-container="body">
                                    @foreach ($categories as $categorie)
                                        <option value="{{$categorie->id}}" data-tokens="{{$categorie->category_name}}">{{$categorie->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="name" class="w-100 mb-2">Tags:</label>
                            <select  name="tags[]" id="tags" value="" class="form-control select2"  multiple="multiple" style="width: 100%;">
                                @foreach ($tags as $tag)
                                    <option value='{{ $tag->id }}'> {{ $tag->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- <div class="col-md-4">
                <div class="card mb-3 mb-4">
                    <div class="card-header">
                        <h4 class="card-header-title mb-0">Organization</h4>
                    </div>

                    <div class="card-body">
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-header-title mb-0">Featured Image</h3>
                    </div>
                    <div class="card-body">
                        <div class="imagePreview pt-2 img-thumbnail">
                            <img id="fileManagerPreview" src="" style="width: 100%">
                        </div>
                        <label class="btn text-primary mt-2 p-0" id="getFileManager">Select featured image</label>
                        <input type="hidden" id="fileManagerId" name="thumbnail">
                    </div>
                </div>

            </div> -->
        </div>

        <div class="position-fixed start-50 bottom-0 translate-middle-x w-100 zi-99 mb-3" style="max-width: 40rem;">
        <!-- Card -->
        <div class="card card-sm bg-dark border-dark mx-2">
          <div class="card-body">
            <div class="row justify-content-center justify-content-sm-between">
              <div class="col">
                <button type="button" class="btn btn-danger">Cancel</button>
              </div>
              <!-- End Col -->

              <div class="col-auto">
                <div class="d-flex gap-3">
                  <button type="button" class="btn btn-light">Save Draft</button>
                  <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
              </div>
              <!-- End Col -->
            </div>
            <!-- End Row -->
          </div>
        </div>
        <!-- End Card -->
      </div>
    </form>
    <div id="fileManagerContainer"></div>

    <div id='ajaxCalls'>
    </div>