<?php use Carbon\Carbon; ?>
<tr data-id="<?=$key?>">

    @if(isset($detailorder))
     <input type="hidden"  name="orderPurchase[<?=$key?>][id]" value="{{ $detailorder->id ?? '' }}">
    @endif
    <td style="width: 40%">
        <div>
            <select class="select2 form-control product_id" data-change="hiden-error"  name="orderPurchase[<?=$key?>][product_id]">
                <option value="">--Seleccionar--</option>
                <?php if (isset($detailorder->product)): ?>
                    <option value="<?= $detailorder->product->id; ?>" selected=""><?= $detailorder->product->name; ?></option>
                <?php endif; ?>
            </select>
        </div>
    </td>
    <td>
        <div>
            <input type="number" name="orderPurchase[<?=$key?>][quantity]" class="form-control" value="{{ $detailorder->quantity ?? '' }}" placeholder="#">
        </div>

    </td>
    <td class="remove_row_td" style="width: 10px;">

        <a class="btn btn-outline-danger" data-click="removeRowOrderPurchase" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>