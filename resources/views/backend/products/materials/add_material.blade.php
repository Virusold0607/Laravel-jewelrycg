<!-- Add Material Modal -->
<style>
    .select2-dropdown{
        z-index: 99999;
    }
</style>
<div class="modal fade" id="modalAddMaterial{{ $material->id }}" data-material_id="{{ $material->id }}" tabindex="-1" aria-labelledby="momdalAddMaterialLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="momdalAddMaterialLabel">Add {{ $material->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            @if ($material->id == 1)
            <div class="modal-body">
                <div class="mb-3">
                    <label for="selMaterialType" class="col-form-label">Diamond Types:</label>
                    <select id="selMaterialType" class="form-control">
                        @foreach ($material->types as $material_type)
                            <option value="{{ $material_type->id }}">{{ $material_type->type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="DiamondSize" class="col-form-label">Diamond Size:</label>
                    <select id="DiamondSize" name="DiamondSize" value="" class="form-control select2" multiple="multiple" style="width: 100%;">
                        @if(isset($arrDiamondTypes) && count($arrDiamondTypes) > 0)
                            @foreach ($arrDiamondTypes as $diamondType)
                                <option value="{{ $diamondType->id }}">{{ $diamondType->mm_size }} mm</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="mb-3" id="sizeSetValues">
                </div>
            </div>
            @else
            
            <div class="modal-body">
                <div class="mb-3">
                    <label for="selMaterialType" class="col-form-label">Material Types:</label>
                    <select id="selMaterialType" class="form-control">
                        @foreach ($material->types as $material_type)
                            <option value="{{ $material_type->id }}">{{ $material_type->type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="txtMaterialWeight" class="col-form-label">Material Weight:</label>
                    <input type="text" class="form-control" id="txtMaterialWeight">
                </div>
            </div>
            @endif
            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="button" class="btn btn-primary btn-add-material" data-material-id="{{ $material->id }}">{{$material->id == 1 ? 'Update' : 'Add' }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    
    $(document).ready(function() {
        
        
        // var diamond_ids = $("input[name^='diamond_id']").map(function (idx, ele) {
        //         return $(ele).val();
        //     }).get();
        // for (let index = 0; index < diamond_ids.length; index++) {
        //     const element = diamond_ids[index];
        //     $("#DiamondSize option[value='"+element+"']").remove();
        // }
    });
</script>