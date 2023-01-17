@php
    use App\Models\ProductMaterial;

    $materials = ProductMaterial::where('product_id', $product->id)
        ->get();
@endphp

<div id="divMaterials">
    @include('backend.products.length.items')
</div>

