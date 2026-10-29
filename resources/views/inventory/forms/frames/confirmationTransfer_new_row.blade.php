<?php use Carbon\Carbon; ?>
<tr data-id="<?=$key?>">
    
   

    @if(isset($tranferInventory) && !$order)
     <input type="hidden"  name="confirmationTransfer[<?=$key?>][id]" value="{{ $tranferInventory->id ?? '' }}">
    @endif
    <td style="width: 40px;">
        <input type="hidden" value="{{$tranferInventory->facturable ?? 0}}" name="confirmationTransfer[<?=$key?>][facturable]">
        @if($tranferInventory->facturable) <div style="position: relative;top: 5px;"><span class="badge badge-danger" title="Facturable">$$</span></div> @endif
    </td>
    <td style="width: 30%">
        <div>
           
            <input type="hidden" value="{{$tranferInventory->product->id ?? ''}}" name="confirmationTransfer[<?=$key?>][product_id]">
            <select class="select2 form-control product_id" data-key="{{$key}}"  name="confirmationTransfer[<?=$key?>][product_id]" readonly="readonly">
                <option value="">--Seleccionar--</option>
                <?php if (isset($tranferInventory->product)): ?>
                    <option value="<?= $tranferInventory->product->id; ?>" selected=""><?= $tranferInventory->product->name; ?></option>
                <?php endif; ?>
            </select>
        </div>
    </td>
    <td>
        <div>
            <input type="text" name="confirmationTransfer[<?=$key?>][foxcon]" class="form-control text-center" readonly value="{{ $tranferInventory->foxcon ?? '' }}">
        </div>
    </td>    
    <td>
        <div>
            <input type="text" name="confirmationTransfer[<?=$key?>][plot]" class="form-control text-center" readonly value="{{ $tranferInventory->plot ?? '' }}">
        </div>
    </td>
    <td>
        <div>
            <input type="text" name="confirmationTransfer[<?=$key?>][date_expiration]" class="form-control text-center" readonly value="{{ $tranferInventory->date_expiration ?? '' }}">
        </div>
    </td>
    <td>
        <div>
            <input type="number" name="confirmationTransfer[<?=$key?>][transfer_quantity]" class="form-control text-center" readonly value="{{ $tranferInventory->transfer_quantity ?? '' }}" placeholder="#">
        </div>
    </td>
    {{-- <td>
        <div class="row icheck_minimal skin">
            <div class="mechanismRadio" style="left: 15px!important;">
                <fieldset>
                        <input type="radio" class="confirmationTransfer" value="1"  data-id="{{$key}}" name="confirmationTransfer[{{$key}}][approved]" checked>
                        <label for="input-radio-5">Si</label>
                </fieldset>
            </div>
            <div class="mechanismRadio" style="left: 13px!important;">
                <div class="skin-states">
                    <fieldset>
                            <input type="radio" class="confirmationTransfer" value="0" data-id="{{$key}}" name="confirmationTransfer[{{$key}}][approved]">
                            <label for="input-radio-6">No</label>
                    </fieldset>
                </div>
            </div>
        </div>
    </td> --}}
    <td>
        <div>
            <input type="number" name="confirmationTransfer[<?=$key?>][quantity_received]"  data-id="<?=$key?>" readonly value="{{ $tranferInventory->transfer_quantity ?? '' }}" class="form-control text-center" placeholder="#">
        </div>
    </td>
    <!--<td class="remove_row_td" style="width: 10px;">

        <a class="btn btn-outline-danger" data-click="removeRowDetailConfirmationTransfer" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td> -->
</tr>