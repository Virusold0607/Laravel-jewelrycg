<div class="card col-md-12 mb-6">
    <!-- Header -->
    <div class="card-header">
        <h4 class="card-header-title mb-0"> Length </h4>
        <a href="{{ route('backend.products.product_lengths', $product->id) }}"
        class="btn btn-sm btn-primary btn-add-material-modal"> Add Length</a>
    </div>
    <!-- End Header -->

    <div class="card-body row">
        <table class="table table-thead-bordered table-nowrap table-align-middle card-table table-responsive no-footer">
            <thead>
            <tr>
                <th>Value</th>
                <th>Measurement</th>
            </tr>
            </thead>

            <tbody class="length_list">
            @php
                $cur_product_attribute_value_id = -1;
            @endphp
            @if(count( $product->measurements ) == 0)
                <tr>
                    <td colspan="2" class="text-danger">No Data to display</td>
                </tr>
            @endif
            @foreach($product_measurements = $product->measurements as $k => $product_measurement)
                @if($product_measurement->product_attribute_value_id != $cur_product_attribute_value_id)
                    <tr data-metal-attribute-value-id="{{ $product_measurement->product_attribute_value_id }}">
                        <td colspan="2">
                            <div class="text-primary">{{ $product_measurement->attribute_name ? $product_measurement->attribute_name : 'No Attribute' }}</div>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>
                        {{ $product_measurement->value }}
                    </td>
                    <td>
                        {{ $product_measurement->measurement_name }}
                    </td>
                </tr>
                @php
                    $cur_product_attribute_value_id = $product_measurement->product_attribute_value_id;
                @endphp
            @endforeach
            </tbody>
        </table>
    </div> 
</div>
