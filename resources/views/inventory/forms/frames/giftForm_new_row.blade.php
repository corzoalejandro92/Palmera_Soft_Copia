<?php use Carbon\Carbon; ?>
<tr data-id="<?=$key?>">
    <td style="width: 5%;"><input type="text" style="height: 42px;text-align: center;" disabled value="{{$key}}"> </td>
    @if(isset($detailTranferInventory))
    <input type="hidden" {{$disabled}} name="transferInventory[<?=$key?>][id]" value="{{ $detailTranferInventory->id ?? '' }}">
    @endif
    <td style="width: 30%;">
        <div>
            <select class="select2  form-control product_id" {{$disabled}} data-key="{{$key}}" data-change="hiden-error"
                name="transferInventory[<?=$key?>][product_id]">
                <?php if (isset($detailTranferInventory->product)): ?>
                <option value="<?= $detailTranferInventory->product->id; ?>" selected=""><?= $detailTranferInventory->product->name; ?></option>
                <?php endif; ?>
            </select>
        </div>
    </td>
    <td style="width: 15%;">
        <div>
            <select {{$disabled}} class="select2 form-control text-center" data-product="{{isset($detailTranferInventory->product) ? $detailTranferInventory->product->id : ''}}" data-change="hiden-error" name="transferInventory[<?=$key?>][plot]"
                data-key="{{$key}}">
                <option value="">--Seleccionar--</option>
                <?php if (isset($detailTranferInventory->plot)): ?>
                    <option value="<?= $detailTranferInventory->plot; ?>" selected="">
                        <?= $detailTranferInventory->plot; ?>
                    </option>
                <?php endif; ?>
            </select>
        </div>
    </td>
    <td  style="width: 20%;">
        <div class="input-group">
            @if(isset($detailTranferInventory->quantity))
                <input type="number" {{$disabled}} value="{{$detailTranferInventory->quantity  ?? ''}}" name="transferInventory[<?=$key?>][quantity]"   class="form-control" placeholder="#">
            @else
            <input type="number" {{$disabled}} data-keyup="hiden-error" value="{{$detailTranferInventory->quantity ?? ''}}" name="transferInventory[<?=$key?>][quantity]"  class="form-control" placeholder="#" aria-label="Recipient's username" aria-describedby="basic-addon2">
                
            @endif
        </div>
    </td>
   
    
    <td style="width: 20%;">
        <div class="input-group-append">
        @if (isset($detailTranferInventory->quantity))
            <span class="input-group-text">En stock:</span>
            <input type="text" id="quantity{{$key}}" data-key="{{$key}}" {{$disabled}} value="0"  class="form-control text-center stock" readonly >
            <div class="input-group-append  unity">
                <span class="input-group-text unit{{$key}}">{{isset($detailTranferInventory->product)?  (!empty($detailTranferInventory->product->unit->name) ? explode(' ',$detailTranferInventory->product->unit->name)[0] : 'N/N'):  'N/N' }}</span>
            </div>
        @else
            <div class="input-group-append">
                    <span class="input-group-text">Stock:</span>
                    <input type="text" id="quantity{{$key}}" data-key="{{$key}}" {{$disabled}} class="form-control text-center stock" placeholder="#" aria-label="Recipient's username" aria-describedby="basic-addon2" readonly >
                <div class="input-group-append unity">
                    <span class="input-group-text unit{{$key}}">{{isset($detailTranferInventory->product)? (!empty($detailTranferInventory->product->unit->name) ? $detailTranferInventory->product->unit->name : 'N/N') : 'N/N' }}</span>
                </div>
            </div>
        @endif
    </td>
    @if(!isset($detailTranferInventory->id))
        <td class="remove_row_td" style="width: 10px;">
            <a class="btn btn-outline-danger {{$disabled}}" data-click="removeRowGift" data-id="<?=$key?>">
                <i class="la la-trash-o"></i>
            </a>
        </td>
    @endif
</tr>