<tr data-id="<?=$key?>">
    @if(isset($settingProductWarehouse))
     <input type="hidden"  name="setting[<?=$key?>][id]" value="{{ $settingProductWarehouse->id ?? '' }}">
    @endif
    <td style="width: 60%;">
        <div>
            <select class="select2 form-control product_id"  data-change="hiden-error"  name="setting[<?=$key?>][product_id]">
                <option value="">--Seleccionar--</option>
                <?php if (isset($settingProductWarehouse->product)): ?>
                    <option value="<?= $settingProductWarehouse->product->id; ?>" selected=""><?= $settingProductWarehouse->product->name; ?></option>
                <?php endif; ?>
            </select>
        </div>
    </td>
    <td>
        <div>
            <button data-click="restarMinimo" data-id="<?=$key?>"
                class="btn btn-social-icon  btn-soundcloud"><span
                    class="la la-minus"></span>
            </button>
        </div>
    </td>
    <td>
        <div class="inputMinimunVal">
            <input type="number" style="text-align: center;" class="form-control" name="setting[<?=$key?>][minimum]"
                value="{{$settingProductWarehouse->minimum ?? ''}}" placeholder="#">
        </div>
    </td>
    <td>
        <div>
            <button data-click="sumarMinimo" data-id="<?=$key?>"
                class="btn btn-social-icon  btn-soundcloud"><span class="la la-plus"></span>
            </button>
        </div>
    </td>
    <td>
        <div>
            <button data-click="restarMaximo" data-id="<?=$key?>"
                class="btn btn-social-icon  btn-soundcloud"><span
                    class="la la-minus"></span></button>
        </div>
    </td>
    <td>
        <div class="inputMaximunVal">
            <input type="number" style="text-align: center;" name="setting[<?=$key?>][maximum]"
                class="form-control" value="{{$settingProductWarehouse->maximum ?? ''}}"
                placeholder="#">
        </div>
    </td>
    <td>
        <div>
            <button data-click="sumarMaximo" data-id="<?=$key?>"
                class="btn btn-social-icon  btn-soundcloud"><span
                    class="la la-plus"></span></button>
        </div>
    </td>
    <td class="remove_row_td" style="width: 10px;">

        <a class="btn btn-outline-danger" data-click="removeRowSetting" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>