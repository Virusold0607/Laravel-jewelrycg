@foreach ($arrMaterials as $material)
<div class="card col-md-12 mb-6">
    <!-- Header -->
    <div class="card-header">
        <h4 class="card-header-title mb-0">{{ $material->name }}</h4>

        <button type="button" class="btn btn-sm btn-primary btn-add-material-modal"
            data-bs-toggle="modal" data-bs-target="#modalAddMaterial{{ $material->id }}" data-material_id="{{ $material->id }}"
        >Add {{ $material->name}}</button>
    </div>
    <!-- End Header -->

    <div class="card-body row">
        <table class="table table-thead-bordered table-nowrap table-align-middle card-table table-responsive no-footer">
            <thead>
                @if($material->id == 1)
                <tr>
                    <th>Diamond</th>
                    <th>Diamond size</th>
                    <th>Diamond amount</th>
                    <th>Clarity</th>
                    <th>Color</th>
                    <th>Lab Price</th>
                    <th>Natural Price</th>
                    <th class='text-center action'>Action</th>
                </tr>
                @else
                <tr>
                    <th>Material</th>
                    <th>Material Weight</th>
                    <th class='text-center action'>Action</th>
                </tr>
                @endif
            </thead>
        
            <tbody class="meterial_list_{{$material->id}}">
                @if (isset($arrProductMaterials[$material->id]) && count($arrProductMaterials[$material->id]) > 0)
                    @foreach ($arrProductMaterials[$material->id] as $product_material)
                        <tr class="pm{{$product_material->diamond_id}}_pmt{{$product_material->material_type_id}}_m{{$material->id}}">
                            <!-- {{-- <td>{{ $product_material->material_type }}</td> --}} -->
                            <td>{{ $product_material->material_type_name }}</td>
                            <input type="hidden" class="form-control" id="product_material_id" name="product_material_id[]" value="{{ $product_material->id }}" />
                            @if($material->id == 1)
                            <td>{{ $product_material->mm_size }} mm</td>
                            <td><input type="number" name="diamond_amount[]" class="form-control" value="{{ $product_material->diamond_amount }}" /></td>
                            <td>
                                <select class="form-control" name="diamond_clarity[]">
                                @if (isset($arrDiamondTypeClarity) && count($arrDiamondTypeClarity) > 0)
                                    @foreach ($arrDiamondTypeClarity as $clarity)
                                    @if(isset($diamondPrices[$product_material->diamond_id]) && count($diamondPrices[$product_material->diamond_id]) > 0)
                                    <option value="{{$clarity->id}}" {{$clarity->id == $diamondPrices[$product_material->diamond_id]['clarity'] ? "selected" : ""}}>{{$clarity->name}}({{$clarity->letters}})</option>
                                    @else
                                    <option value="{{$clarity->id}}">{{$clarity->name}}({{$clarity->letters}})</option>
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="diamond_color[]">
                                @if (isset($arrDiamondTypeColors) && count($arrDiamondTypeColors) > 0)
                                    @foreach ($arrDiamondTypeColors as $color)
                                    @if(isset($diamondPrices[$product_material->diamond_id]) && count($diamondPrices[$product_material->diamond_id]) > 0)
                                    <option value="{{$color->id}}" {{$color->id == $diamondPrices[$product_material->diamond_id]['color'] ? "selected" : ""}}>{{$color->name}}({{$color->letters}})</option>
                                    @else
                                    <option value="{{$color->id}}">{{$color->name}}({{$color->letters}})</option>
                                    @endif
                                    @endforeach
                                @endif
                                </select>
                            </td>
                            @if(isset($diamondPrices[$product_material->diamond_id]) && count($diamondPrices[$product_material->diamond_id]) > 0)
                            <td><input type="text" name="lab_price[]" class="form-control" value="{{ $diamondPrices[$product_material->diamond_id]['lab_price'] }}" /></td>
                            @else
                            <td><input type="text" name="lab_price[]" class="form-control" value="" /></td>
                            @endif
                            @if(isset($diamondPrices[$product_material->diamond_id]) && count($diamondPrices[$product_material->diamond_id]) > 0)
                            <td><input type="text" name="natural_price[]" class="form-control" value="{{ $diamondPrices[$product_material->diamond_id]['natural_price'] }}" /></td>
                            @else
                            <td><input type="text" name="natural_price[]" class="form-control" value="" /></td>
                            @endif
                            <input type="hidden" name="material_weight[]" class="form-control" value="{{ $product_material->material_weight }}" />
                            <input type="hidden" class="form-control" id="diamond_id" name="diamond_id[]" value="{{ $product_material->diamond_id }}" />
                            <input type="hidden" class="form-control" name="diamond_mmsize[]" value="{{ $product_material->mm_size }} mm" />
                            <input type="hidden" class="form-control" name="material_typename[]" value="{{ $product_material->material_type_name }}" />
                            @else
                            <input type="hidden" name="diamond_amount[]" class="form-control" value="{{ $product_material->diamond_amount }}" />
                            <td><input type="number" name="material_weight[]" class="form-control" value="{{ $product_material->material_weight }}" /></td>
                            @endif
                            <td class='text-center action'>
                                <input type="hidden" class="form-control" id="material_type_id" name="material_type_id[]" value="{{ $product_material->material_type_id }}" />
                                <input type="hidden" class="form-control" id="material_id" name="material_id[]" value="{{ $material->id }}" />
                                <input type="hidden" class="form-control" id="is_diamond" name="is_diamond[]" value="{{ $material->id == 1 ? 1: 0 }}" />
                                <button type="button" class="btn btn-sm btn-danger btn-delete-material"
                                    data-id="{{ $product_material->id }}" data-material_id="{{$material->id}}"
                                >Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="none-material-message{{$material->id}}">
                        <td colspan="4" class="text-center">No Materials</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@include('backend.products.materials.add_material')
@include('backend.products.materials.edit_material')
@endforeach
