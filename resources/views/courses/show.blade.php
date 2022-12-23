<x-app-layout page-title="Courses">

    <section class="bg-white py-8">
        <div class="container">
            <div class="col-xl-11 mx-auto">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="fw-800">{{ $course->name }}</h1>
                    </div>
                    <div class="col-lg-4">
                        <div class="p-3 border mb-4">
                            <h1 class="text-primary">$99</h1>
                            <a type="button" class="btn btn-primary w-100" href="/courses/checkout/{{$course->id}}">Buy</a>
                        </div>
                        <div class="p-3 border">
                            <h1 class="text-black">Course Syllabus</h1>
                            <div class="course-content-list">
                                <h3 class="fw-700">First Lesson</h3>
                                <ul class="list-style-none">
                                    <li>Lesson 1</li>
                                    <li>Lesson 2</li>
                                    <li>Lesson 3</li>
                                </ul>
                            </div>
                            <div class="course-content-list">
                                <h3 class="fw-700">Second Lesson</h3>
                                <ul class="list-style-none">
                                    <li>Lesson 1</li>
                                    <li>Lesson 2</li>
                                </ul>
                            </div>
                        </div>
                    </div>
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
            </div>
        </div>
    </section>
</x-app-layout>
    
