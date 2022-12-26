<x-app-layout page-title="Courses">
    <section class="bg-white pt-9 pb-8">
        <div class="container">
            <div class="col-xl-10 mx-auto">
                <div class="row">
                    <div class="col-lg-4">
                        @foreach ($course->lessons as $i => $lesson)
                        <div class="nav-item">
                            <a class="nav-link nav-link-main dropdown-toggle fs-4 pt-2 pb-2" href="#navbar" role="button" data-bs-toggle="collapse" data-bs-target="#navbar-{{$i}}" aria-expanded="false">
                              <span class="nav-link-title">{{ $lesson->name }}</span>
                            </a>
                            <div id="navbar-{{$i}}" class="nav-collapse collapse @if($i == 0) show @endif">
                                @foreach ($lesson->contents as $j => $content)
                                <div class="d-flex align-items-center icon-{{$i}}-{{$j}}">
                                    <a class="nav-link fs-5 lesson" role="button" lesson="{{$i}}" content="{{$j}}">{{ $content->name }}</a>
                                    @if ($history = $content->history)
                                    <i class="bi bi-check-lg"></i>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
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

        var lesson = 0;
        var content = 0;
        var course = @php echo json_encode($course) @endphp;
        var button = "complete & continue <i class='bi bi-arrow-right'></i>";
        var icon ="<i class='bi bi-check-lg'></i>";

        $('.title').html(course.lessons[lesson].contents[content].name)
        $('.description').html(course.lessons[lesson].contents[content].content)
        $('.complete').html(button);
        $('.hidden').val(course.lessons[lesson].contents[content].id);



      $(document).on('click', '.lesson', function() {
        lesson = $(this).attr('lesson');
        content = $(this).attr('content');



       $('.title').html(course.lessons[lesson].contents[content].name)
       $('.description').html(course.lessons[lesson].contents[content].content)
       $('.complete').html(button);
       $('.hidden').val(course.lessons[lesson].contents[content].id);

      })

      $('.complete').on('click', function() {
        var id = $('.hidden').val();
        var url = "{{ url('courses/take/complete') }}";
        if(course.lessons[lesson].contents[content].history){
            if(course.lessons[lesson].contents.length - 1 == content ){
                lesson = lesson+1;
            }else{
                content = content+1;
            }

            $('.title').html(course.lessons[lesson].contents[content].name)
            $('.description').html(course.lessons[lesson].contents[content].content)
            $('.complete').html(button);
            $('.hidden').val(course.lessons[lesson].contents[content].id);
            $(`#navbar-${lesson}`).addClass('show');

        }else {
            $.ajax({
                url: url+"/"+id,
                method: 'get',
                success: function(result) {

                    $(`.icon-${lesson}-${content}`).append(icon);
                    if(course.lessons.length -1 == lesson){
                        return;
                    }else if(course.lessons[lesson].contents.length - 1 == content ){
                        lesson = lesson + 1;
                        content = 0;

                        $('.title').html(course.lessons[lesson].contents[content].name)
                        $('.description').html(course.lessons[lesson].contents[content].content)
                        $('.complete').html(button);
                        $('.hidden').val(course.lessons[lesson].contents[content].id);
                        $(`#navbar-${lesson}`).addClass('show');
                    }else{
                        content = content + 1;

                        $('.title').html(course.lessons[lesson].contents[content].name)
                        $('.description').html(course.lessons[lesson].contents[content].content)
                        $('.complete').html(button);
                        $('.hidden').val(course.lessons[lesson].contents[content].id);
                        $(`#navbar-${lesson}`).addClass('show');
                    }
            
                }
            });
        }
      })
    });
</script>