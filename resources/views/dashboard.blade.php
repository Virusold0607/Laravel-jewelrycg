<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}
      </h2>
  </x-slot>

  <div class="py-9">
      <div class="container">
          @if (auth()->user()->role == 2)
              <div class="header mb-4">
                  <ul class="nav nav-pills">
                      <li class="nav-item">
                          <a class="nav-link {{ \Route::currentRouteName() == 'seller.dashboard' ? 'active' :'' }}" href="\seller\dashboard">Seller Dashboard</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link {{ \Route::currentRouteName() == 'dashboard' ? 'active' :'' }}" href="\dashboard">User Dashboard</a>
                      </li>
                  </ul>
              </div>
          @endif
          <div class="row">
              <div class="col-lg-4">
                  <div class="card py-3">
                      <div class="card-body">
                          <h4 class="card-title">{{ $carts }} Products</h4>
                          <h6 class="card-subtitle mb-2 text-muted">in your cart</h6>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4">
                  <div class="card py-3">
                      <div class="card-body">
                          <h4 class="card-title">{{ $wishlists }} Products</h4>
                          <h6 class="card-subtitle mb-2 text-muted">in your wishlist</h6>
                      </div>
                  </div>
              </div>
              <div class="col-lg-4">
                  <div class="card py-3">
                      <div class="card-body">
                          <h4 class="card-title">{{ $orders }} Products, {{ $service_orders }} Services, {{ $course_orders }} Courses</h4>
                          <h6 class="card-subtitle mb-2 text-muted">you ordered</h6>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Your Purchases</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($purchases as $item)
                                <div class="col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            @if ($item->product_variant == 0)
                                                <a href="{{ route('orders.show', $item->order_id) }}">
                                                    <img src="{{ $item->uploads->getImageOptimizedFullName(400) }}"
                                                        alt="" style="width: 100%;" class="mb-3 pb-3 border-bottom">
                                                </a>
                                                <a href="{{ route('orders.show', $item->order_id) }}">
                                                    <h6>{{ $item->product_name }}</h6>
                                                </a>
                                            @else
                                                <a href="{{ route('orders.show', $item->order_id) }}">
                                                    <img src="{{ $item->uploads->getImageOptimizedFullName(400) }}"
                                                        alt="" style="width: 100%;" class="mb-3 pb-3 border-bottom">
                                                </a>
                                                <a href="{{ route('orders.show', $item->order_id) }}">
                                                    <h6>{{ $item->product_name }} - {{ $item->product_variant_name }}</h6>
                                                </a>
                                            @endif
                                            <button class="btn btn-danger w-100">
                                                <i class="bi bi-link"></i> Create Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{$purchases->appends(Arr::except(Request::query(), 'product'))->links()}}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Your Service Orders</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($services as $item)
                                @isset($item->service)
                                    @isset($item->service->uploads)

                                        <div class="col-xl-6 col-lg-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img src="{{ $item->service->uploads->getImageOptimizedFullName(400) }}"
                                                    alt="" style="width: 100%;" class="mb-3 pb-3 border-bottom">
                                                    <a href="/services/{{ $item->slug }}">
                                                        <h6>{{ $item->service->name }} - {{ $item->package_name }}</h6>
                                                    </a>
                                                    {{-- <a class="btn btn-primary" id="download" href="{{ url('/product/download/') . $item->id }}">
                                                        <i class="bi bi-download"></i> Download
                                                    </a> --}}
                                                    <a class="btn btn-primary" href="/services/order/{{$item->order_id}}">
                                                        <i class="bi bi-link"></i> View Order
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endisset
                                @endisset
                            @endforeach
                        </div>
                        {{$services->appends(Arr::except(Request::query(), 'service'))->links()}}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Your Course Orders</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($courses as $item)
                                <div class="col-xl-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <img src="{{ $item->course->uploads->getImageOptimizedFullName(400) }}"
                                            alt="" style="width: 100%;" class="mb-3 pb-3 border-bottom">
                                            <a href="/courses/course/{{ $item->course->slug }}">
                                                <h6>{{ $item->course->name }}</h6>
                                            </a>
                                            {{-- <a class="btn btn-primary" id="download" href="{{ url('/product/download/') . $item->id }}">
                                                <i class="bi bi-download"></i> Download
                                            </a> --}}
                                            <a class="btn btn-primary" href="/courses/course/{{ $item->course->slug }}">
                                                <i class="bi bi-link"></i> View Course
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{$courses->appends(Arr::except(Request::query(), 'course'))->links()}}
                    </div>
                </div>
            </div>

          </div>
      </div>
  </div>
</x-app-layout>
