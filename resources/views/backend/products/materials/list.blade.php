@php
use App\Models\ProductMaterial;

$materials = ProductMaterial::where('product_id', $product->id)
    ->get();
@endphp

<div id="divMaterials">
    @include('backend.products.materials.items')

</div>

@push('material_scripts')
<script>

var product_id = {{ $product->id }};
var cur_product_material_id = 0;

$(document).ready(function() {
    
    var list = [];
    var DiamondSize = $('#DiamondSize').select2({
        tags: true,
        maximumSelectionLength: 100,
        tokenSeparators: [','],
        placeholder: "Select or type keywords",
    })
    $('body').on('click', '.btn-delete-material', function() {
        isButtonClicked = true;
        var product_material_id = $(this).data('id');
        var material_id = $(this).data('material_id');
        var deletedItem = '<input type="hidden" class="form-control" id="deleted_material_ids[]" name="deleted_material_ids[]" value="'+product_material_id+'" />'
        $(".meterial_list_"+material_id).append(deletedItem);
        if (confirm('Do you want to delete this material really?')) {
            // $.ajax({
            //     type: 'DELETE',
            //     url: "{{ route('backend.products.materials.delete') }}",
            //     data: {
            //         "_token": "{{ csrf_token() }}",
            //         "material_id": material_id,
            //     },
            //     dataType: "json",
            //     success: (result) => {
            //         var materials_html = result.materials_html;
            //         replaceMaterialsHtml(materials_html);
            //     },
            //     error: (resp) => {
            //         var result = resp.responseJSON;
            //         if (result.errors && result.message) {
            //             alert(result.message);
            //             return;
            //         }
            //     }
            // });
            $(this).parent().parent('tr').remove();
        }
    });

    $('body').on('click', '.btn-add-material-modal', function() {
        var material_id = $(this).data('material_id');
        if (material_id == 1) {
            var modal = $(this).data('bs-target');
            $(modal + ' #txtMaterialWeight').val('');
            var diamond_ids = $("input[name^='diamond_id']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            var diamond_mmsizes = $("input[name^='diamond_mmsize']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            var diamond_amounts = $("input[name^='diamond_amount']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            var material_type_ids = $("input[name^='material_type_id']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            var product_material_ids = $("input[name^='product_material_id']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            
            var selectedValues = [];
            let tempelement = '';

            material_type_ids.map( function (item, key){
                itemdatatemp = {
                    "diamond_id": diamond_ids[key], 
                    "diamond_mmsize": diamond_mmsizes[key], 
                    "diamond_amount": diamond_amounts[key], 
                    "material_type_id": material_type_ids[key], 
                    "product_material_id": product_material_ids[key], 
                }
                if(list[item] === undefined){
                    list[item] = [itemdatatemp]
                } else {
                    list[item].push(itemdatatemp);
                }
            })
            
            
            let uIndexs = [...new Set(material_type_ids)];

            for (let index = 0; index < list[uIndexs[0]].length; index++) {
                const itemdata = list[uIndexs[0]][index];
                const diamond_id = itemdata['diamond_id'];
                const diamond_mmsize = itemdata['diamond_mmsize'];
                const diamond_amount = itemdata['diamond_amount'];
                const product_material_id = itemdata['product_material_id'];
                const material_type_id = itemdata['material_type_id'];
                if(diamond_id > 0){
                    selectedValues.push(diamond_id);
                    tempelement +=   '<div class="row" id="add_diamond_'+diamond_id+'">' + 
                                        '<div class="col-4">'+
                                            '<label for="diamondSizeId" class="col-form-label">Value</label>'+
                                            '<input type="hidden" class="form-control" name="diamondId[]" value="' + diamond_id + '">'+
                                            '<input type="hidden" class="form-control" name="product_materialid[]" value="' + product_material_id + '">'+
                                            '<input type="hidden" class="form-control" name="diamond_mmsize[]" value="' + diamond_mmsize + '">'+
                                            '<h6 id="diamondSizeId" name="diamond_sizename[]">' + diamond_mmsize + '</h6>'+
                                        '</div>'+
                                        '<div class="col-8">'+
                                            '<label for="diamondAmount" class="col-form-label">Amount</label>'+
                                            '<input type="text" class="form-control" name="diamondAmount[]" value="'+ diamond_amount +'">'+
                                        '</div>'+
                                    ' </div>';
                }
            }
            DiamondSize.val(selectedValues).trigger("change");
            
            $("#selMaterialType").val(uIndexs[0]);
            $('#sizeSetValues').html(tempelement)
        }
    });

    
    $('#DiamondSize').on('select2:select', function (e) {
        let data = e.params.data;
        let tempelement =   '<div class="row" id="add_diamond_'+data.id+'">' + 
                                '<div class="col-4">'+
                                    '<label for="diamondSizeId" class="col-form-label">Value</label>'+
                                    '<input type="hidden" class="form-control" name="diamondId[]" value="' + data.id + '">'+
                                    '<input type="hidden" class="form-control" name="product_materialid[]" value="">'+
                                    '<h6 id="diamondSizeId" name="diamond_sizename[]">' + data.text + '</h6>'+
                                '</div>'+
                                '<div class="col-8">'+
                                    '<label for="diamondAmount" class="col-form-label">Amount</label>'+
                                    '<input type="text" class="form-control" name="diamondAmount[]">'+
                                '</div>'+
                           ' </div>';
        $('#sizeSetValues').append(tempelement)
    });

    $('#DiamondSize').on('select2:unselect', function(e) {
        let data = e.params.data;
        material_id = 1;
        let deleted_item = $("#add_diamond_"+ data.id).children().children('input[name^="product_materialid"]').val();
        if(deleted_item) {
            let deletedItem = '<input type="hidden" class="form-control" id="deleted_material_ids[]" name="deleted_material_ids[]" value="'+deleted_item+'" />'
            $(".meterial_list_"+material_id).append(deletedItem);
        }
        $("#add_diamond_"+ data.id+"").remove();
    })

    $('#selMaterialType').on('change', function(e){
        let newValue = e.target.value;
        let diamond_sizes = [];
        let total_diamond_sizes = {!! json_encode($arrDiamondTypes) !!};
        let optiondata = '';
        total_diamond_sizes.map(function(item) {
            if(item['material_type_id'] == newValue) {
                optiondata += '<option value="'+item['id']+'">'+item['mm_size']+' mm</option>';
            }
        })
        $('#DiamondSize').html(optiondata);

        let tempelement = '';
        let selectedValues = [];
        if (list[newValue]) {
            for (let index = 0; index < list[newValue].length; index++) {
                const itemdata = list[newValue][index];
                const diamond_id = itemdata['diamond_id'];
                const diamond_mmsize = itemdata['diamond_mmsize'];
                const diamond_amount = itemdata['diamond_amount'];
                const product_material_id = itemdata['product_material_id'];
                const material_type_id = itemdata['material_type_id'];
                if(diamond_id > 0){
                    selectedValues.push(diamond_id);
                    tempelement +=   '<div class="row" id="add_diamond_'+diamond_id+'">' + 
                                        '<div class="col-4">'+
                                            '<label for="diamondSizeId" class="col-form-label">Value</label>'+
                                            '<input type="hidden" class="form-control" name="diamondId[]" value="' + diamond_id + '">'+
                                            '<input type="hidden" class="form-control" name="product_materialid[]" value="' + product_material_id + '">'+
                                            '<input type="hidden" class="form-control" name="diamond_mmsize[]" value="' + diamond_mmsize + '">'+
                                            '<h6 id="diamondSizeId" name="diamond_sizename[]">' + diamond_mmsize + '</h6>'+
                                        '</div>'+
                                        '<div class="col-8">'+
                                            '<label for="diamondAmount" class="col-form-label">Amount</label>'+
                                            '<input type="text" class="form-control" name="diamondAmount[]" value="'+ diamond_amount +'">'+
                                        '</div>'+
                                    ' </div>';
                }
            }
        }
        DiamondSize.val(selectedValues).trigger("change");
        $('#sizeSetValues').html(tempelement)
        
    });

    $('#modalAddMaterial1').on('hidden.bs.modal', function () {
        list = []
    })

    $('body').on('click', '.btn-add-material', function() {
        list = []
        var material_id = $(this).data('material-id');
        var material_type_id = $('#modalAddMaterial' + material_id +  ' #selMaterialType').val();
        var material_weight = $('#modalAddMaterial' + material_id + ' #txtMaterialWeight').val();
        var material_typename = $("#modalAddMaterial" + material_id + " #selMaterialType option:selected" ).text();
        if (material_id == 1) {
            var diamond_ids = $('#DiamondSize').val();
            var diamond_amounts = $("input[name^='diamondAmount']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            var product_material_ids = $("input[name^='product_materialid']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            var diamond_sizenames = $("h6[name^='diamond_sizename']").map(function (idx, ele) {
                return $(ele).html();
            }).get();
            var deleted_material_ids = $("input[name^='deleted_material_ids']").map(function (idx, ele) {
                return $(ele).val();
            }).get();
            var product_mids = $("input[name^='product_material_id']").map(function (idx, ele) {
                return $(ele).val();
            }).get();            
            var diamondIds = $("input[name^='diamond_id']").map(function (idx, ele) {
                return $(ele).val();
            }).get();            
        } else {
            var diamond_amounts = '';
            var diamond_ids = [];
        }
        $('#modalAddMaterial' + material_id + ' #txtMaterialWeight').val('');
        $('#modalAddMaterial' + material_id +  ' #selMaterialType').val('');
        $('#modalAddMaterial' + material_id + ' #diamondAmount').val('');
        var trItemdata = '';
        if (material_id == 1) {
            product_mids.map(function(product_mid, key){
                for (let i = 0; i < deleted_material_ids.length; i++) {
                    const element = deleted_material_ids[i];
                    if(element == product_mid){
                        console.log(key, diamondIds)
                        console.log(".pm"+diamondIds[key]+"_pmt"+material_type_id+"_m"+material_id)
                        $(".pm"+diamondIds[key]+"_pmt"+material_type_id+"_m"+material_id).remove()
                    }
                }
            })
            for (let index = 0; index < diamond_amounts.length; index++) {
                let diamond_amount = diamond_amounts[index];
                let diamond_sizename = diamond_sizenames[index];
                let diamond_id = diamond_ids[index];
                let diamondPrices = {!! json_encode($diamondPrices) !!};
                let clarities = {!! json_encode($arrDiamondTypeClarity) !!};
                let colors = {!! json_encode($arrDiamondTypeColors) !!};
                let product_material_id = product_material_ids[index];
                if($('.pm'+diamond_id+'_pmt'+material_type_id+'_m'+material_id).length) {

                    let rowdata ='<td>' + material_typename + '</td>'+
                                '<input type="hidden" class="form-control" id="product_material_id" name="product_material_id[]" value="'+(product_material_id != undefined ? product_material_id: '' )+'" />'+
                                '<td>'+diamond_sizename+'</td>'+
                                '<td><input type="number" name="diamond_amount[]" class="form-control" value="'+diamond_amount+'" /></td>'+
                                '<td>'+
                                    '<select class="form-control" name="diamond_clarity[]">';
                                    if (typeof(clarities)) {
                                        clarities.map(function(clarity){
                                            if(typeof(diamondPrices[diamond_id])){
                                                rowdata += '<option value="'+ clarity["id"] +'" '+ (clarity["id"] == diamondPrices[diamond_id]["clarity"] ? "selected" : "")+'>'+ clarity["name"] +'('+ clarity["letters"] +')</option>';
                                            } else {
                                                rowdata += '<option value="'+ clarity["id"] +'">'+ clarity["name"] +'('+ clarity["letters"] +')</option>';
                                            }
                                        })
                                    }
                                rowdata += '</select>'+
                                '</td>'+
                                '<td>'+
                                    '<select class="form-control" name="diamond_color[]">';
                                    if (typeof(colors)){
                                        colors.map(function(color){
                                            if(typeof(diamondPrices[diamond_id])){
                                                rowdata += '<option value="'+ color["id"] +'" '+ (color["id"] == diamondPrices[diamond_id]["color"] ? "selected" : "")+'>'+ color["name"] +'('+ color["letters"] +')</option>';
                                            } else {
                                                rowdata += '<option value="'+ color["id"] +'">'+ color["name"] +'('+ color["letters"] +')</option>';
                                            }
                                        })
                                    }
                                rowdata += '</select>'+
                                '</td>'+
                                '<td><input type="text" name="lab_price[]" class="form-control" value="'+ diamondPrices[diamond_id]["lab_price"] +'" /></td>'+
                                '<td><input type="text" name="natural_price[]" class="form-control" value="'+ diamondPrices[diamond_id]["natural_price"] +'" /></td>'+
                                '<input type="hidden" name="material_weight[]" class="form-control" value="" />'+
                                '<td class="text-center action">'+
                                    '<input type="hidden" class="form-control" id="diamond_id" name="diamond_id[]" value="'+diamond_id+'" />'+
                                    '<input type="hidden" class="form-control" id="material_type_id" name="material_type_id[]" value="'+material_type_id+'" />'+
                                    '<input type="hidden" class="form-control" id="material_id" name="material_id[]" value="'+material_id+'" />'+
                                    '<input type="hidden" class="form-control" id="is_diamond" name="is_diamond[]" value="' + (material_id == 1 ? 1 : 0) + '" />'+
                                    '<input type="hidden" class="form-control" name="diamond_mmsize[]" value="'+diamond_sizename+'" />'+
                                    '<button type="button" class="btn btn-sm btn-danger btn-delete-material" data-id="" >Delete</button>'+
                                '</td>';
                    $('.pm'+diamond_id+'_pmt'+material_type_id+'_m'+material_id).html(rowdata);
                } else {

                    let rowdata = '<tr class="pm'+diamond_id+'_pmt'+material_type_id+'_m'+material_id+'">'+
                                '<td>' + material_typename + '</td>'+
                                '<input type="hidden" class="form-control" id="product_material_id" name="product_material_id[]" value="'+(product_material_id != undefined ? product_material_id: '' )+'" />'+
                                '<td>'+diamond_sizename+'</td>'+
                                '<td><input type="number" name="diamond_amount[]" class="form-control" value="'+diamond_amount+'" /></td>'+
                                '<td>'+
                                    '<select class="form-control" name="diamond_clarity[]">';
                                    if (typeof(clarities)){
                                        clarities.map(function(clarity){
                                            rowdata += '<option value="'+ clarity["id"] +'">'+ clarity["name"] +'('+ clarity["letters"] +')</option>';
                                        })
                                    }
                                rowdata += '</select>'+
                                '</td>'+
                                '<td>'+
                                    '<select class="form-control" name="diamond_color[]">';
                                    if (typeof(colors)){
                                        colors.map(function(color){
                                            rowdata += '<option value="'+ color["id"] +'">'+ color["name"] +'('+ color["letters"] +')</option>';
                                        })
                                    }
                                rowdata += '</select>'+
                                '</td>'+
                                '<td><input type="text" name="lab_price[]" class="form-control" value="" /></td>'+
                                '<td><input type="text" name="natural_price[]" class="form-control" value="" /></td>'+
                                '<input type="hidden" name="material_weight[]" class="form-control" value="" />'+
                                '<td class="text-center action">'+
                                    '<input type="hidden" class="form-control" id="diamond_id" name="diamond_id[]" value="'+diamond_id+'" />'+
                                    '<input type="hidden" class="form-control" id="material_type_id" name="material_type_id[]" value="'+material_type_id+'" />'+
                                    '<input type="hidden" class="form-control" id="material_id" name="material_id[]" value="'+material_id+'" />'+
                                    '<input type="hidden" class="form-control" id="is_diamond" name="is_diamond[]" value="' + (material_id == 1 ? 1 : 0) + '" />'+
                                    '<input type="hidden" class="form-control" name="diamond_mmsize[]" value="'+diamond_sizename+'" />'+
                                    '<button type="button" class="btn btn-sm btn-danger btn-delete-material" data-id="" >Delete</button>'+
                                '</td>'+
                            '</tr>';
                    $(".meterial_list_"+material_id).append(rowdata);
                }
            }
        } else {
            trItemdata = '<tr>'+
                        '<td>' + material_typename + '</td>'+
                        '<input type="hidden" class="form-control" id="product_material_id" name="product_material_id[]" value="" />';
                        if(material_id == 1) {
                            trItemdata += '<td>'+diamond_sizename+'</td>'+
                            '<td><input type="number" name="diamond_amount[]" class="form-control" value="'+diamond_amount+'" /></td>'+
                            '<input type="hidden" name="material_weight[]" class="form-control" value="" />';
                        } else {
                            trItemdata += '<input type="hidden" name="diamond_amount[]" class="form-control" value="" />'+
                            '<td><input type="number" name="material_weight[]" class="form-control" value="'+material_weight+'" /></td>';
                        }
                        trItemdata += '<td class="text-center action">'+
                            '<input type="hidden" class="form-control" id="diamond_id" name="diamond_id[]" value="" />'+
                            '<input type="hidden" class="form-control" id="material_type_id" name="material_type_id[]" value="'+material_type_id+'" />'+
                            '<input type="hidden" class="form-control" id="material_id" name="material_id[]" value="'+material_id+'" />'+
                            '<input type="hidden" class="form-control" id="is_diamond" name="is_diamond[]" value="' + (material_id == 1 ? 1 : 0) + '" />'+
                            '<button type="button" class="btn btn-sm btn-danger btn-delete-material" data-id="" >Delete</button>'+
                        '</td>'+
                    '</tr>';
            $(".meterial_list_"+material_id).append(trItemdata);
        }
        if(material_id == 1) {
            if($('.none-material-message'+material_id).length){
                $('.none-material-message'+material_id).remove()
            }
            $('#DiamondSize').val(null).trigger('change');
            $('#modalAddMaterial' + material_id).modal('hide');        
            // $('#sizeSetValues').html('');
        } else {
            if($('.none-material-message'+material_id).length){
                $('.none-material-message'+material_id).remove()
            }
            $('#modalAddMaterial' + material_id).modal('hide');
        }
        
        // $.ajax({
        //     type: 'POST',
        //     url: "{{ route('backend.products.materials.store') }}",
        //     data: {
        //         "_token": "{{ csrf_token() }}",
        //         "product_id": product_id,
        //         "material_type_id": material_type_id,
        //         "material_weight": material_weight,
        //         "diamond_amount": diamond_amount,
        //         "diamond_ids": diamond_ids
        //     },
        //     dataType: "json",
        //     success: (result) => {
        //         $('#modalAddMaterial' + material_id).modal('hide');
        //         var materials_html = result.materials_html;
        //         replaceMaterialsHtml(materials_html);
        //     },
        //     error: (resp) => {
        //         var result = resp.responseJSON;
        //         if (result.errors && result.message) {
        //             alert(result.message);
        //             return;
        //         }
        //     }
        // });
    });

    $('body').on('click', '.btn-edit-material', function() {
        var modal = $(this).data('bs-target');
        cur_product_material_id = $(this).data('id');
        var material_type_id = $(this).data('material-type-id');
        var material_weight = $(this).data('material-weight');
        var diamond_size = $(this).data('diamond-size');
        var diamond_sizename = $(this).data('diamond-sizename');
        var diamond_amount = $(this).data('diamond-amount');

        $(modal + ' #selMaterialType').val(material_type_id);
        $(modal + ' #txtMaterialWeight').val(material_weight);
        $(modal + ' #editDiamondSizeId').val(diamond_size);
        $(modal + ' #editDiamondSizeName').html(diamond_sizename+"mm");
        $(modal + ' #editDiamondAmount').val(diamond_amount);
    });

    $('body').on('click', '.btn-update-material', function() {
        var material_id = $(this).data('material-id');
        var material_type_id = $('#modalEditMaterial' + material_id + ' #selMaterialType').val();
        var material_weight = $('#modalEditMaterial' + material_id + ' #txtMaterialWeight').val();
        var diamond_amount = $('#modalEditMaterial' + material_id + ' #editDiamondAmount').val();
        $.ajax({
            type: 'POST',
            url: "{{ route('backend.products.materials.update') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "_method": "PUT",
                "id": cur_product_material_id,
                "product_id": product_id,
                "material_type_id": material_type_id,
                "material_weight": material_weight,
                "diamond_amount": diamond_amount,
            },
            dataType: "json",
            success: (result) => {
                $('#modalEditMaterial' + material_id).modal('hide');
                var materials_html = result.materials_html;
                replaceMaterialsHtml(materials_html);
            },
            error: (resp) => {
                var result = resp.responseJSON;
                if (result.errors && result.message) {
                    alert(result.message);
                    return;
                }
            }
        });
    });
});

var replaceMaterialsHtml = function(materials_html) {
    $('#divMaterials').html(materials_html);
}
</script>

@endpush
