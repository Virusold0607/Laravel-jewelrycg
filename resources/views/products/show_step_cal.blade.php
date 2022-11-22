@php
use App\Models\CurrentRate;
$current_rate = CurrentRate::getLastRate();
@endphp

@if (count($arrProductMaterials))
<div class="section-header">
    <div class="section-header-title mb-3 text-uppercase fw-700 border p-3">Cost to Make Calculator</div>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info" role="alert"><strong>Step 1:</strong> Select the metal below you want to make this item with.</div>
        <div id="">
            <div class="accordion-body">
                <div class="row">
                    @foreach ($arrProductMaterials as $product_material)
                        @php
                            $type_name = $product_material->type;
                            $type_id = $product_material->material_type_id;
                            $material_weight = $product_material->material_weight;
                            $material_dwt = $material_weight * 0.64301;
                            $diamond_tamount = 0;
                            foreach($arrProductDiamonds as $diamond) {
                                $diamond_tamount += $diamond->diamond_amount;
                            }

                            if ($current_rate == null) {
                                $price = 0;
                                $price_change = 0;
                            } else {
                                if (strpos($type_name ,'24K') !== false) {
                                    $rate = $current_rate['24k'];
                                } else if (strpos($type_name ,'22K') !== false) {
                                    $rate = $current_rate['22k'];
                                } else if (strpos($type_name ,'18K') !== false) {
                                    $rate = $current_rate['18k'];
                                } else if (strpos($type_name ,'14K') !== false) {
                                    $rate = $current_rate['14k'];
                                } else if (strpos($type_name ,'10K') !== false) {
                                    $rate = $current_rate['10k'];
                                }

                                $price = number_format($material_weight * $rate, 2, '.', ',');
                            }
                        @endphp

                        <div class="col-lg-4 col-6">
                            <div class="border p-3 item-value-card mb-3 cal-select-item" data-metal_id="{{$type_id}}">
                                <div class="item-value-card-body">
                                    <div class="value-title pb-2 mb-2 text-uppercase fw-700">
                                        {{ $type_name }}
                                    </div>
                                    <div class="py-1">
                                        <span class="value-price">${{ $price }}</span>
                                        <input type="hidden" name="metal_price" class="metal_price" value="{{ $price }}" />
                                        <input type="hidden" name="metal_price_rate" class="metal_price_rate" value="{{ round($rate, 2) }}" />
                                        <input type="hidden" name="metal_weight" class="metal_weight" value="{{ $material_weight }}" />
                                    </div>
                                    <div class="py-1 fw-700 fs-24">{{ $material_weight }} Grams</div>
                                    <div class="py-1 fw-700 fs-14">{{ $material_dwt }} DWT</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="alert alert-info" role="alert"><strong>Step 2:</strong> Select the type of diamond you want to below.</div>
        <div>
            <div class="accordion-body">
                <div class="row">                        
                    <div class="col-lg-6 col-6">
                        <div class="border p-3 item-value-card mb-3 diamondtype-select-item" data-diamondtype_id="1">
                            <div class="item-value-card-body">
                                <div class="py-1 fw-700 fs-24">Natural Diamonds</div>
                                <div class="py-1 fw-700 fs-14">Natural Diamonds</div>
                            </div>
                        </div>
                    </div>                        
                    <div class="col-lg-6 col-6">
                        <div class="border p-3 item-value-card mb-3 diamondtype-select-item" data-diamondtype_id="2">
                            <div class="item-value-card-body">
                                <div class="py-1 fw-700 fs-24">Lab Diamonds</div>
                                <div class="py-1 fw-700 fs-14">Lab Diamonds</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-body">
            <div class="table-full-width">
                <div class="col-md-12">
                    <table id="product_total_estimate_price" class="table table-lg table-bordered table-nowrap table-align-middle card-table dataTable table-responsive no-footer">
                        <thead>
                            <th>Estimated Cost</th>
                            <th id="total_estimate_price"></th>
                        </thead>
                        <tbody>
                            <tr class="metal_price">
                                <td class="metal_price_category"></td>
                                <td class="metal_total_price"></td>
                            </tr>
                            
                            @foreach ($arrProductDiamonds as $diamond)
                                <tr class="natural_price">
                                    <td class="product_diamond_category">{{ $diamond->mm_size }} mm ({{$diamond->tcw}} * ${{ $diamond->natural_price }})</td>
                                    <td class="product_diamond_price">${{ ($diamond->tcw * $diamond->natural_price) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($arrProductDiamonds as $diamond)
                                <tr class="lab_price">
                                    <td class="product_diamond_category">{{ $diamond->mm_size }} mm ({{$diamond->tcw}} * ${{ $diamond->lab_price }})</td>
                                    <td class="product_diamond_price">${{ ($diamond->tcw * $diamond->lab_price) }}</td>
                                </tr>
                            @endforeach
                            <tr class="printing_cost">
                                <td class="printing_cost_title">3D Printing Cost</td>
                                <td class="printing_cost_amount"></td>
                            </tr>
                            @if($diamond_tamount > 0)
                            <tr class="diamond_setting_cost">
                                <td class="diamond_setting_cost_title">Diamond Setting Cost ({{ $diamond_tamount }} x $1.5)</td>
                                <td class="diamond_setting_cost_amount">${{ round($diamond_tamount * 1.5, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="casting_cost">
                                <td>Casting Cost</td>
                                <td class="casting_cost_amount"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    var diamondtype_id = 1;
    var estimatedPrice = 0;
    $('.cal-select-item').first().addClass('active');
    var metal_category = $('.cal-select-item.active .value-title').html()
    var metal_price = $('.cal-select-item.active .value-price').html()
    var metal_price_rate = $('.cal-select-item.active .metal_price_rate').val()
    var metal_weight = $('.cal-select-item.active .metal_weight').val()
    var td_data = metal_category + ' - ($' + metal_price_rate + '/gram * ' + metal_weight + 'gram)';
    $('.metal_price_category').html(td_data)
    $('.metal_total_price').html(metal_price)

    $('.diamondtype-select-item').first().addClass('active')
    diamondtype_id = $('.diamondtype-select-item.active').data('diamondtype_id')
    if(diamondtype_id == 1) {
        $('.natural_price').show()
        $('.lab_price').hide()
    } else {
        $('.natural_price').hide()
        $('.lab_price').show()
    }
    getEstimatePrice()

    $('.cal-select-item').on('click', function(){
        $('.cal-select-item').removeClass('active')
        $(this).addClass('active')
        var metal_category = $('.cal-select-item.active .value-title').html()
        var metal_price = $('.cal-select-item.active .value-price').html()
        var metal_price_rate = $('.cal-select-item.active .metal_price_rate').val()
        var metal_weight = $('.cal-select-item.active .metal_weight').val()
        var td_data = metal_category + ' - ($' + metal_price_rate + '/gram * ' + metal_weight + 'gram)';
        $('.metal_price_category').html(td_data)
        $('.metal_total_price').html(metal_price)
        var type_id = $(this).data('type_id')
        getEstimatePrice()
    })
    $('.diamondtype-select-item').on('click', function(){
        $('.diamondtype-select-item').removeClass('active')
        $(this).addClass('active')
        diamondtype_id = $(this).data('diamondtype_id')
        if(diamondtype_id == 1) {
            $('.natural_price').show()
            $('.lab_price').hide()
        } else {
            $('.natural_price').hide()
            $('.lab_price').show()
        }
        getEstimatePrice()
    })
    function getEstimatePrice(){
        var estimatedPrice = 0;
        var metal_price = Number(($('.cal-select-item.active .value-price').html()).replace('$','').replace(',',''))
        var metal_weight = Number($('.cal-select-item.active .metal_weight').val())
        var print_cost = metal_weight * 2.5;
        if (print_cost > 24.99){
            goldWeightConst = print_cost.toFixed(2);
            goldWeightConstText = '3D Printing Cost - ($2.50 x '+metal_weight.toFixed(2)+')';
        }else{
            goldWeightConst = 25;
            goldWeightConstText = '3D Printing Cost';
        }
        $('.printing_cost_title').html(goldWeightConstText)
        $('.printing_cost_amount').html("$"+goldWeightConst)
        $('.casting_cost_amount').html("$"+(metal_price*0.15).toFixed(2))
        var diamond_setting_cost = Number(($('.diamond_setting_cost_amount').html()).replace('$', ''))
        
        estimatedPrice += metal_price
        diamondtype_id = $('.diamondtype-select-item.active').data('diamondtype_id')
        if(diamondtype_id == 1) {
            $('.natural_price .product_diamond_price').map(function(idx, ele){
                estimatedPrice += Number(($(ele).html()).replace('$', ''))
            })
        } else {
            $('.lab_price .product_diamond_price').map(function(idx, ele){
                estimatedPrice += Number(($(ele).html()).replace('$', ''))
            })
        }
        estimatedPrice += Number(goldWeightConst);
        estimatedPrice += Number((metal_price*0.15).toFixed(2));
        estimatedPrice += diamond_setting_cost;

        $('#total_estimate_price').html('$'+estimatedPrice.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'))
    }
</script>
