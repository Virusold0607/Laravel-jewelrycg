<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Dashboard') }}
      </h2>
  </x-slot>

  <div class="py-9">
      <div class="container">
          <div class="row">
              <div class="col-md-8">
                  <h3>Services</h3>
              </div>
              <div class="col-md-12">
                <div class="row row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-1">
                  @foreach ($services as $service)
                  <div class="col mb-4 mb-lg-0">
                    <div class="card">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-lg-4">
                            <a href="/services/{{$service->slug}}" class=""">
                              <img src="{{ $service->uploads->getImageOptimizedFullName(400,400) }}" class="rounded w-100 border" alt="{{ $service->name }}">
                            </a>
                          </div>
                          <div class="col-lg-8">
                            <div class="fs-20 fw-700 mb-2">
                              <a href="/services/{{$service->slug}}" class="mt-2">{{ $service->name }}</a>
                            </div>
                            @foreach ($service->categories as $item)
                            <div class="fs-14 mb-2 fw-700 opacity-70">{{ $item->category->category_name }}</div>
                            @endforeach

                            <div class="d-flex align-items-center mb-2">
                              <div class="mr-10px">
                                <img class="w-30px rounded-circle" src="{{ $service->postauthor->uploads->getImageOptimizedFullName(100,100) }}" alt="{{ $service->postauthor->first_name }}">
                              </div>
                              <div class="col-">{{ $service->postauthor->first_name." ".$service->postauthor->last_name }}</div>
                            </div>
                            
                          </div>
                        </div>
                      </div>
                      <div class="card-footer bg-white">
                      <div class="row">
                        <div class="col-6">
                            @if ($service->count > 0)
                              <span><i class="bi bi-star-fill fs-20 text-warning"></i> {{ $service->rating ?: "0.0" }}</span>
                              <span class="text-secondary">({{$service->count}})</span>
                            @endif
                        </div>
                        <div class="col-6">
                          <div class="text-right">Starting at <span class="fw-700 fs-16 text-primary">{{ count($service->packages) ? "$".($service->packages[0]->price / 100) : "..." }}</span></div>
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>
