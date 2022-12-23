@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>{{ $message }}</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


@if ($errors->any())

    <ul class="errors text-danger p-0">
        @foreach ($errors->all() as $error)
            <li class="error d-flex justify-content-center">{{ $error }}</li>
        @endforeach
    </ul>

@endif