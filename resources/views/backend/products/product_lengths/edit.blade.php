@extends('backend.layouts.app', ['activePage' => 'products', 'title' => 'Edit Product', 'navName' => 'Table List', 'activeButton' => 'catalogue'])
@section('css_content')
    <style>
        .select2-dropdown {
            z-index: 99999;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-30px">
        <div class="d-flex align-items-end">
            <img src="{{ $product->uploads->getImageOptimizedFullName(400) }}" class="w-50px">
            <h2>{{ $product->name }}</h2>
        </div>

        <a href="{{ route('backend.products.edit', $product->id) }}" class="btn btn-sm btn-primary">Back to product</a>
    </div>

    <form method="POST" action="{{ route('backend.products.update_product_lengths') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Length</h3>
                <button class="btn btn-sm btn-primary btn-add-material-modal" type="button" data-bs-toggle="modal"
                        data-bs-target="#add_length_modal">
                    Add Length</button>
            </div>
            <div class="table-responsive mb-20px">
                <table class="table table-bordered table-responsive"
                       id="length_table">
                    <thead>
                    <tr>
                        <th>Value</th>
                        <th>Measurement</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody style="font-size: 1rem; vertical-align: middle;">
                    @php
                        $cur_product_attribute_value_id = -1;
                    @endphp
                    @foreach($product_attributes_group_by as $k => $product_attribute)
                        @if(isset($product_attribute))
                            <tr data-length-attribute-id="{{ $product_attribute['id'] }}">
                                @if(count($product_attribute['measurements']) == 0)
                                    <div class="text-danger">No Data to display</div>
                                @else
                                    <td colspan="3">
                                        <div class="text-primary">
                                            {{ $product_attribute['attribute_name'] ? $product_attribute['attribute_name'] : 'No Attribute' }}
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endif

                        @foreach($product_attribute['measurements'] as $measurement)
                            <tr data-unique-key="metal_{{ $product_attribute['id'] . '_' . $measurement['id'] }}"
                                data-length-attribute-id="{{ $product_attribute['id'] }}">
                                <td>
                                    <input type="number" inputmode="decimal" step="0.01" name="value[]"
                                            value="{{ $measurement['value'] }}" class="form-control">
                                    <input type="hidden" name="measurement_id[]"
                                            value="{{ $measurement['id'] }}">
                                    <input type="hidden" name="length_product_attribute_value_id[]"
                                            value="{{ $product_attribute['id'] }}">
                                </td>
                                <td>
                                    {{ $measurement['name']  }} {{ $measurement['unit'] }}
                                </td>
                                <td>
                                    <button class="form-control btn btn-danger btn-sm"
                                            onclick="delete_current_length_row(this)">Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        

        <div class="text-right">
            <button class="btn btn-success ml-auto" type="submit">Update</button>
        </div>
    </form>


    <div id="add_length_modal" class="modal modal-lg fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="font-size: 1rem;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLengthLabel">Add Length</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="length_attribute_values_select">Attribute values:</label>
                        <select id="length_attribute_values_select" class="form-control select2"
                                multiple="multiple" style="width: 100%;">
                            @include('backend.products.attributes.values.ajax',[
                                'attributes' => $product_attributes,
                                'values_selected' => explode(',', $product->product_attribute_values),
                                'can_select_other_options' => false
                            ])
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="length_measurement_select">Measurement</label>
                        <select id="length_measurement_select" class="form-control">
                            @foreach($measurements as $measurement)
                                <option value="{{ $measurement->id }}">{{ $measurement->name . ' ' . $measurement->units }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-add-material"
                            onclick="add_length_product_materials()">Add
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js_content')
    <script>
      let measurements = @json($measurements);
      let attribute_values = @json($attribute_values);

      $('.select2').select2({
        tags: true,
        maximumSelectionLength: 10,
        tokenSeparators: [','],
        placeholder: "Select or type keywords",
      })


      let delete_current_length_row = function (ele) {
        let tr = ele.closest('tr')
        if ($('tr[data-length-attribute-id="' + tr.dataset.lengthAttributeId + '"]').length == 2) {
          $('tr[data-length-attribute-id="' + tr.dataset.lengthAttributeId + '"]').remove()
        }
        tr.remove()
      }


      $('document').ready(function () {
        $('#length_attribute_values_select').val([]).trigger('change');
      })

      let add_length_product_materials = function () {
        let length_attribute_values = $('#length_attribute_values_select').val().length ? $('#length_attribute_values_select').val() : [0]
        let length_measurement_value = $('#length_measurement_select').val();
        // debugger
        for (let i = 0; i < length_attribute_values.length; i++) {
          new_html = '';
          /* if product_material already existed */
          if (!$('tr[data-unique-key="metal_' + length_attribute_values[i] + '_' + length_measurement_value + '"]').length) {
            attribute_value = attribute_values.find(attribute_value => attribute_value.id == length_attribute_values[i])
            measurement = measurements.find(measurement => measurement.id == length_measurement_value)

            /* if current attribute_value id is not in current table */
            if ($('tr[data-length-attribute-id="' + length_attribute_values[i] + '"]').length == 0) {
              new_html = '<tr data-length-attribute-id="' + length_attribute_values[i] + '"><td colspan="3"><div class="text-primary">' + (attribute_value ? attribute_value.value + ' ' + attribute_value.name : 'No Attribute') + '</div></td></tr>'
            }

            new_html += '<tr data-product-material-id="0" data-unique-key="metal_' + length_attribute_values[i] + '_' + length_measurement_value + '" data-length-attribute-id="' + length_attribute_values[i] + '">' +
              '<td> <input type="number" inputmode="decimal" step="0.01" name="value[]" value="0" class="form-control">' +
              '<input type="hidden" name="measurement_id[]" value="' + length_measurement_value + '">' +
              '<input type="hidden" name="length_product_attribute_value_id[]" value="' + length_attribute_values[i] + '">' +
              '</td>';

            new_html += '<td>'+ measurement.name + ' ' + measurement.units +'</td>'
            new_html += '<td><button class="form-control btn btn-danger btn-sm" onclick="delete_current_length_row(this)">Delete</button></td>'

            new_html += '</tr>';

            if ($('tr[data-length-attribute-id="' + length_attribute_values[i] + '"]').length) {
              $('tr[data-length-attribute-id="' + length_attribute_values[i] + '"]:last')[0].insertAdjacentHTML('afterend', new_html)
            } else {
              $('#length_table tbody')[0].insertAdjacentHTML('beforeend', new_html)
            }
          }
        }

        $('#add_length_modal').modal('hide')

        $('#length_attribute_values_select').val([]).trigger("change");

        $('.select2').select2({
          tags: true,
          maximumSelectionLength: 10,
          tokenSeparators: [','],
          placeholder: "Select or type keywords",
        })
      }
    </script>
@endsection
