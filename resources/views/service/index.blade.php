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
                            <img src="{{ $service->uploads->getImageOptimizedFullName(400,400) }}" class="rounded w-100" alt="{{ $service->name }}">
                          </div>
                          <div class="col-lg-8">
                            <div class="card-title">{{ $service->name }}</div>
                            @foreach ($service->categories as $item)
                            <div class="fs-14">{{ $item->category->category_name }}</div>
                            @endforeach
                            <div class="row">
                              <div class="col-3">
                                <img style="w-100" src="{{ $service->postauthor->uploads->getImageOptimizedFullName(100,100) }}" alt="{{ $service->postauthor->first_name }}">
                              </div>
                              <div class="col-9">{{ $service->postauthor->first_name." ".$service->postauthor->last_name }}</div>
                            </div>

                            <ul class="list-unstyled d-flex justify-content-start align-items-center fs-6 mb-2">
                              <li>Start Price:</li>
                              <li>
                                <div class="chip ms-3">{{ count($service->packages) ? "$".($service->packages[0]->price / 100) : "..." }}</div>
                              </li>
                            </ul>
                            @if ($service->count > 0)
                            <ul class="list-unstyled d-flex justify-content-start align-items-center fs-6 mb-3">
                              <li>
                                <span><i class="bi bi-star-fill fs-20 text-warning"></i> {{ $service->rating ?: "0.0" }}</span>
                                <span class="text-secondary">({{$service->count}})</span>
                              </li>
                            </ul>
                            @endif
                            <a href="/services/{{$service->slug}}" class="btn btn-primary">Details</a>
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
