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
                <div class="row row-cols-xxl-6 row-cols-xl-4 row-cols-lg-4 row-cols-md-4 row-cols-2">
                  @foreach ($services as $service)
                  <div class="col mb-4 mb-lg-0">
                    <div class="card">
                      <img src="{{ $service->uploads->getImageOptimizedFullName(400,400) }}" class="card-img-top" alt="{{ $service->name }}" data-xblocker="passed" style="visibility: visible;">
                      <div class="card-body">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <ul class="list-unstyled d-flex justify-content-start align-items-center fs-6 mb-2">
                          <li>Category:</li>
                          @foreach ($service->categories as $item)
                          <li>
                            <div class="chip ms-3">{{ $item->category->category_name }}</div>
                          </li>
                          @endforeach
                        </ul>
                        <ul class="list-unstyled d-flex justify-content-start align-items-center fs-6 mb-2">
                          <li>Seller Name:</li>
                          <li>
                            <div class="chip ms-3">{{ $service->postauthor->first_name." ".$service->postauthor->last_name }}</div>
                          </li>
                        </ul>
                        <ul class="list-unstyled d-flex justify-content-start align-items-center fs-6 mb-2">
                          <li>Seller Avatar:</li>
                          <li>
                            <img style="width: 50px" src="{{ $service->postauthor->uploads->getImageOptimizedFullName(100) }}" alt="{{ $service->postauthor->first_name }}">
                          </li>
                        </ul>
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
                  @endforeach
                </div>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>
