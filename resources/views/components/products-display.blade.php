<div class="row row-cols-xxl-6 row-cols-xl-4 row-cols-lg-4 row-cols-md-4 row-cols-2">
    @foreach ($products->chunk(4) as $products_chunk)
        @foreach ($products_chunk as $product)
            <div class="mt-1 mb-4 col">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="mb-2 card">
                        <img src="{{ $product->uploads->getImageOptimizedFullName(400) }}" alt="{{ $product->name }}" class="rounded w-100 lazyloaded">
                    </div>
                    <div class="text-left px-2">
                        <div class="fw-700 fs-16 text-primary col-8">
                            {{ $product->price }}
                        </div>
                        <h3 class="mb-0 text-black fw-600 fs-16 text-truncate-2 lh-1-4 h-35px">
                            {{ $product->name }}
                        </h3>
                    </div>
                </a>
            </div>
        @endforeach
    @endforeach
</div>
<div class="row mt-5">
    {{ $products->links() }}
</div>
