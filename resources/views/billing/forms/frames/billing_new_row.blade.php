<?php use Carbon\Carbon; ?>
<tr data-id="<?=$key?>">

    @if(isset($schedule))
    <input type="hidden" name="billing[<?=$key?>][id]" value="{{ $bill->id ?? '' }}">
    <input type="hidden" name="billing[<?=$key?>][schedule_id]" value="{{ $schedule->id ?? '' }}">
    @endif
    <td style="width: 50%;">
        <div>
            <input type="text" name="billing[<?=$key?>][name]" value="{{$schedule->service->name ?? '' }}"
                class="form-control" placeholder="Servicio">
        </div>
    </td>
    <td style="width: 10%;">
        <div>
            <input type="number" name="billing[<?=$key?>][quantity]" value="1"
                class="form-control text-center" placeholder="Cantidad">
        </div>
    </td>
    <td>
        <div>
            <input type="text" id="value{{$key}}" data-format="1" onkeyup="calc()" name="billing[<?=$key?>][valor]"
                value="{{ number_format(($schedule->price ?? 0),0,".",",")}}" class="form-control" placeholder="">
        </div>
    </td>
    @if(isset($schedule->id))

    @else
        <td class="remove_row_td" style="width: 10px;">

            <a class="btn btn-outline-danger" data-click="removeRow" data-id="<?=$key?>">
                <i class="la la-trash-o"></i>
            </a>
        </td>
    @endif


</tr>