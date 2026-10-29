<?php use Carbon\Carbon; ?>
<tr data-id="<?=$key?>">

    @if(isset($editInventory))
     <input type="hidden"  name="inventory[<?=$key?>][id]" value="{{ $detailInventory->id ?? '' }}">
    @endif
    <td>
        <div>
        <input type="text" class="form-control" style="text-transform: uppercase;" data-keyup="hiden-error" name="inventory[<?=$key?>][code]" value="{{ $detailInventory->code ?? '' }}" placeholder="#">
        </div>
    </td>
    <td>
        <div>
            <input type="text" class="form-control" style="text-transform: uppercase;" data-keyup="hiden-error" name="inventory[<?=$key?>][plot_input]" value="{{ $detailInventory->plot ?? '' }}" placeholder="#">
        </div>

    </td>
    <td>
        <div>
            <input type="number" name="inventory[<?=$key?>][quantity]" data-keyup="hiden-error" class="form-control" value="{{ $detailInventory->stock ?? '' }}" placeholder="#">
        </div>

    </td>
    <td>
        <div>
            <input class="form-control" name="inventory[<?=$key?>][date_expiration]" value="{{ Carbon::parse($detailInventory->date_expiration ?? '')->format("Y-m-d") }}"  type="date">
        </div>
    </td>
    @if($deleted)
        <td class="remove_row_td" style="width: 10px;">
            <a class="btn btn-outline-danger" data-warehouse="{{ $detailInventory->warehouse_id ?? '' }}" data-click="removeRowEditInventory" data-id="{{ $detailInventory->id ?? '' }}">
                <i class="la la-trash-o"></i>
            </a>
        </td>
    @endif
</tr>