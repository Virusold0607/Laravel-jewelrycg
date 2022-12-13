<x-app-layout>
    <style>
        .pur {
            width: 100%;
            margin-bottom: 8px;
        }

        .carousel {
            margin-bottom: 70px;
        }

        .carousel-indicators li {
            width: 120px;
            height: 100%;
            opacity: 0.8;
            margin: 0px 5px;
        }

        .carousel-indicators button[data-bs-target] {
            width: 120px;
        }

        .carousel-indicators button[data-bs-target]:not(.active) {
            opacity: 0.8;
        }

        .carousel-indicators {
            position: static;
            margin-top: 10px;
            justify-content: normal;
            margin-left: 0;
            margin-right: 0;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-9">
        <div class="container">
            <div class="col-xl-10 mx-auto">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <h1 class="fs-24 w-100 mb-2">{{$service->name}}</h4>
                                <div class="d-flex align-items-center">
                                    <div class="mr-10px w-30px">
                                        <img src="{{ $service->postauthor->uploads->getImageOptimizedFullName(30,30) }}" alt="avatar" class="rounded-circle img-fluid">
                                    </div>
                                    <div class="fs-14 fw-700 mr-10px border-right">
                                        <div class="data">{{ $service->postauthor->full_name }}</div>
                                    </div>
                                
                                    @if ($service->count > 0)
                                        <div class="ml-10px">
                                            <span><i class="bi bi-star-fill fs-20 text-warning"></i> {{ $service->rating ?: "0.0" }}</span>
                                            <span class="text-secondary">({{$service->count}})</span>
                                        </div>
                                    @endif
                                </div>
                        </div>

                        <div class="carousel">
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @for ($i = 0; $i < count($service->galleries); ++$i)
                                        <div class="carousel-item {{ $i == 0 ? "active" : "" }}">
                                            <img src="/uploads/all/{{$service->galleries[$i]->file_name}}"
                                                 class="d-block w-100 border" alt="..."/>
                                        </div>
                                    @endfor
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                   data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                   data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </a>
                                <ol class="carousel-indicators">
                                    @for ($i = 0; $i < count($service->galleries); ++$i)
                                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}"
                                            class="{{$i == 0 ? "active": "" }}">
                                            <img src="/uploads/all/{{$service->galleries[$i]->file_name}}"
                                                 class="d-block w-100 border">
                                        </li>
                                    @endfor
                                </ol>
                            </div>
                        </div>

                        <div class="mb-2">
                            <h4>About This Service</h4>
                            <div>{!! $service->content !!}</div>
                        </div>

                        <div class="row mt-3">
                            <h4>About the seller</h4>
                            <div class="d-flex">
                                <div class="">
                                    <img src="{{ $service->postauthor->uploads->getImageOptimizedFullName(110,110) }}"
                                         alt="avatar"
                                         class="rounded-circle img-fluid">
                                </div>
                                <div class="ml-15px">
                                    <a href="/u/{{ $service->postauthor->username }}"
                                       class="fs-20 fw-700 text-black">{{ $service->postauthor->full_name }}</a>
                                    <p class="mb-5px">{{ $service->seller->slogan == '' ? 'No Slogan' : $service->seller->slogan }}</p>
                                    @if ($rating->count > 0)
                                        <div class="mb-3">
                                            <span><i class="bi bi-star-fill fs-20 text-warning"></i> {{ $rating->rating ?: "0.0" }}</span>
                                            <span class="text-secondary">({{$rating->count}})</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-start mb-2">
                                        <a class="btn btn-primary" href="{{route('create_chat_room',['conversation_id'=>$service->seller->user->id])}}">Message</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row mb-5px">
                                            <div class="col">
                                                <p class="text-muted mb-0">Member since</p>
                                                <div>{{ $service->postauthor->created_at->format('M Y') }}</div>
                                            </div>
                                            <div class="col">
                                                <p class="text-muted mb-0">Avg. response time</p>
                                                <div>{{ !$service->postauthor->get_avg_response_time() == '-' ? '-' : ($service->postauthor->get_avg_response_time() . 'Hours') }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-5px">
                                            <div class="col">
                                                <p class="text-muted mb-0">Last delivery</p>
                                                <div>{{ !$service->postauthor->last_delivery_time() ? 'None' : $service->postauthor->last_delivery_time()->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="text-black" style="border-color: black !important;">

                                    <div class="container">
                                        {{ $service->seller->about }}
                                    </div>
                                </div>

                                <hr>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="service-packages-card border p-3">
                            <ul class="nav nav-pills nav-fill mb-3 bg-dark rounded p-2" id="pills-tab" role="tablist">
                                @foreach ($service->packages as $k => $package)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $k == 0 ? 'active' : '' }}"
                                                id="pills-{{ $package->id }}-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#pills-{{ $package->id }}" type="button" role="tab"
                                                aria-controls="pills-{{ $package->id }}"
                                                aria-selected="true">{{ $package->name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                @foreach ($service->packages as $k => $package)
                                    <div class="tab-pane fade {{ $k == 0 ? 'show active' : '' }}"
                                        id="pills-{{ $package->id }}" role="tabpanel"
                                        aria-labelledby="pills-{{ $package->id }}-tab">
                                        <h3>${{number_format($package->price / 100, 2)}}</h3>
                                        <h4>{{$package->name}}</h4>
                                        <p>{{$package->description}}</p>
                                        <p>{{$package->delivery_time}} Day Delivery</p>
                                        <p>{{$package->revisions}} Revisions</p>
                                        <a href="/services/checkout/{{$package->id}}" type="button"
                                        class="btn btn-info">Continue(${{number_format($package->price / 100, 2)}}
                                            )</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
