<?php use Carbon\Carbon; ?>
<tr data-id="<?=$key?>">

    @if(isset($detailpurchase))
     <input type="hidden"  name="purchase[<?=$key?>][id]" value="{{ $detailpurchase->id ?? '' }}">
    @endif
    <td style="width: 35%;">
        <div>
            <select class="select2 form-control product_id" data-change="hiden-error"  name="purchase[<?=$key?>][product_id]" data-key="{{$key}}">
                <option value="">--Seleccionar--</option>
                <?php if (isset($detailpurchase->product)): ?>
                    <option value="<?= $detailpurchase->product->id; ?>" selected=""><?= $detailpurchase->product->name; ?></option>
                <?php endif; ?>
            </select>
        </div>
    </td>
    <td>
        <div>
        <input type="text" class="form-control" style="text-transform: uppercase;" data-keyup="hiden-error" name="purchase[<?=$key?>][code]" value="{{ $detailpurchase->code ?? '' }}" placeholder="#">
        </div>
    </td>
    <td>
        <div>
            <input type="text" class="form-control" style="text-transform: uppercase;" data-keyup="hiden-error" name="purchase[<?=$key?>][plot_input]" value="{{ $detailpurchase->plot ?? '' }}" placeholder="#">
        </div>

    </td>
    <td>
        <div style="display: inline-flex;">
            <input type="number" name="purchase[<?=$key?>][quantity]" data-keyup="hiden-error" class="form-control" value="{{ $detailpurchase->quantity ?? '' }}" placeholder="#"> <label  class="purchase{{$key}}Unity" style="padding: 5px;margin-top: 3px;color: #6c6c6c;">und</label>
        </div>

    </td>
    <td>
        <div>
            <input class="form-control" name="purchase[<?=$key?>][date_expiration]" value="{{ Carbon::parse($detailpurchase->date_expiration ?? '')->format("Y-m-d") }}"  type="date">
        </div>
    </td>
    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeRowShopping" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>