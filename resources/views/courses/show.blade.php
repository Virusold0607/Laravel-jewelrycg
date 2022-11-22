<x-app-layout page-title="Courses">
    <section>
        <div class="container">
            <div class="col-xl-11 py-8 mx-auto">
                <h1 class="fw-800">{{ $course->name }}</h1>
            </div>
        </div>
    </section>

    <section class="bg-white pb-4">
        <div class="container">
            <div class="col-xl-11 mx-auto">
                <div class="row gutters-10 row-cols-lg-3 row-cols-md-2 row-cols-1">
                    @foreach ($course->lessons as $lesson)
                        <div class="col mb-3">
                            <div class="blog-post-list-container">
                                <div class="p-2 pt-3">
                                    <h2 class="fs-18 fw-600 mb-2">
                                        {{ $lesson->name }}
                                    </h2>
                                    @foreach ($lesson->contents as $content)
                                        <div class="mb-2 opacity-50 article-list-category">
                                            <div>{{ $content->name }} </div>
                                            <div>{{ $content->content }} </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a type="button" class="btn btn-info" href="/courses/checkout/{{$course->id}}">Buy</a>
            </div>
        </div>
    </section>
</x-app-layout>
    