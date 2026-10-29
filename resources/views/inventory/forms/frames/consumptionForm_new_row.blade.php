<?php use Carbon\Carbon; ?>
<tr data-id="<?=$key?>">

    @if(isset($detailTranferInventory) && !$order)
    <input type="hidden" name="transferInventory[<?=$key?>][id]" value="{{ $detailTranferInventory->id ?? '' }}">
    @endif
    <td style="width: 25%;">
        <div>
            <select class="select2 form-control product_id" data-key="{{$key}}" data-change="hiden-error"
                name="transferInventory[<?=$key?>][product_id]">
                <?php if (isset($detailTranferInventory->product)): ?>
                <option value="<?= $detailTranferInventory->product->id; ?>" selected=""><?= $detailTranferInventory->product->name; ?></option>
                <?php endif; ?>
            </select>
        </div>
    </td>
    <td style="width: 22%;">
        <div>
            <select class="select2 form-control text-center" data-product="{{isset($detailTranferInventory->product) ? $detailTranferInventory->product->id : ''}}" data-change="hiden-error" name="transferInventory[<?=$key?>][plot]"
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
    <td  style="width: 25%;">
        <div class="input-group">
            @if(isset($detailTranferInventory->transfer_quantity))
                <input type="number" value="{{$detailTranferInventory->transfer_quantity  ?? ''}}" name="transferInventory[<?=$key?>][transfer_quantity]"   class="form-control" placeholder="#">
            @else
            <input type="number" data-keyup="hiden-error" value="{{$detailTranferInventory->transfer_quantity ?? ''}}" name="transferInventory[<?=$key?>][transfer_quantity]"  class="form-control" placeholder="#" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <div class="input-group-append unity">
                <span class="input-group-text unit{{$key}}">{{isset($detailTranferInventory->product)? explode(' ',$detailTranferInventory->product->unit->name)[0] : '' }}</span>
                </div>
            @endif
        </div>
    </td>




    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeRowTransfer" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
<tr data-id="<?=$key?>-2" style="height: 60px;">
    <td  style="border-bottom: 1px solid #e0e0e0!important">
        <div class="foxcon input-group-append">
            <span class="input-group-text">RSA</span>
                    <input type="text" id="foxcon{{$key}}" name="transferInventory[<?=$key?>][foxcon]" value="{{$detailTranferInventory->foxcon ?? ''}}" class="form-control text-center" readonly>
        </div>
    </td>
    <td style="border-bottom: 1px solid #e0e0e0!important">
        <div class="date input-group-append">
            <span class="input-group-text">Vcto</span>
                    <input id="date{{$key}}" name="transferInventory[<?=$key?>][date_expiration]" value="{{isset($detailTranferInventory->date_expiration) ? \Carbon\Carbon::parse($detailTranferInventory->date_expiration)->format('d-m-Y') : ''}}" class="form-control text-center" readonly>
            </div>
    </td>
    <td style="border-bottom: 1px solid #e0e0e0!important">
        <div class="input-group-append">
        @if (isset($detailTranferInventory->quantity))
            <span class="input-group-text">En stock:</span>
            <input type="text" id="quantity{{$key}}" value="{{$detailTranferInventory->quantity ?? ''}}"  name="transferInventory[<?=$key?>][quantity]"  class="form-control text-center" readonly >
            <div class="input-group-append  unity">
                <span class="input-group-text unit{{$key}}">{{isset($detailTranferInventory->product)?  (!empty($detailTranferInventory->product->unit->name) ? explode(' ',$detailTranferInventory->product->unit->name)[0] : 'N/N'):  'N/N' }}</span>
            </div>
        @else
            <div class="input-group-append">
                    <span class="input-group-text">Stock:</span>
                    <input type="text" id="quantity{{$key}}" name="transferInventory[<?=$key?>][quantity]" value="{{$detailTranferInventory->quantity ?? ''}}" class="form-control text-center" placeholder="#" aria-label="Recipient's username" aria-describedby="basic-addon2" readonly >
                <div class="input-group-append unity">
                    <span class="input-group-text unit{{$key}}">{{isset($detailTranferInventory->product)? (!empty($detailTranferInventory->product->unit->name) ? $detailTranferInventory->product->unit->name : 'N/N') : 'N/N' }}</span>
                </div>
            </div>
        @endif
    </td>

</tr>