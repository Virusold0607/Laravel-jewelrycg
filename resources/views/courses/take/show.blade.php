<x-app-layout page-title="Courses">
    <section class="bg-white pt-9 pb-8">
        <div class="container">
            <div class="col-xl-10 mx-auto">
                <div class="row">
                    <div class="col-lg-4">
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($course->lessons as $lesson)
                        <div class="nav-item">
                            <a class="nav-link nav-link-main dropdown-toggle fs-4 pt-2 pb-2" href="#navbar" role="button" data-bs-toggle="collapse" data-bs-target="#navbar-{{$lesson->id}}" aria-expanded="false">
                              <span class="nav-link-title">{{ $lesson->name }}</span>
                            </a>
                            <div id="navbar-{{$lesson->id}}" class="nav-collapse collapse">
                                @php
                                    $j =0;
                                @endphp
                                @foreach ($lesson->contents as $content)
                                <div class="d-flex align-items-center">
                                    <a class="nav-link fs-5 lesson" role="button" lesson="{{$i}}" content="{{$j}}">{{ $content->name }}</a>
                                    @if ($history = $content->history)
                                    <i class="bi bi-check-lg"></i>
                                    @endif
                                </div>
                                    @php
                                        $j++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                        @php
                            $i++;
                        @endphp
                        @endforeach
                    </div>
                    <div class="col-lg-8">
                        <h1 class="fw-800 mb-4 title">{{ $course->name }}</h1>
                        <input type="hidden" class="hidden">
                        <p class="description"></p>
                        <button class='complete btn btn-light text-uppercase text-right mt-4 float-end' role="button"></button>
                    </div>

                </div>
            </div>
        </div>
    </section>
</x-app-layout>

<script>
    $(function() {


      $(document).on('click', '.lesson', function() {
        var lesson = $(this).attr('lesson');
        var content = $(this).attr('content');
        var course = @php echo json_encode($course) @endphp;

       var div = "complete & continue <i class='bi bi-arrow-right'></i>";

       $('.title').html(course.lessons[lesson].contents[content].name)
       $('.description').html(course.lessons[lesson].contents[content].content)
       $('.complete').html(div);
       console.log(course.lessons[lesson].contents[content].history)
       $('.hidden').val(course.lessons[lesson].contents[content].id);
      })

      $('.complete').on('click', function() {
        var id = $('.hidden').val();
        var url = "{{ url('courses/take/complete') }}";
        $.ajax({
                url: url+"/"+id,
                method: 'get',
                success: function(result) {
                   $('.complete').prop('disabled', true);
                }
            });
      })
    });
</script>