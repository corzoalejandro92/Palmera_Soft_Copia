<tr data-id="<?=$key?>">
    @if($product)
        <input type="hidden" name="products[<?=$key?>][id]" value="{{$product->id}}" data-change="hiden-error" />  
    @endif 
    <td style="width: 70%;">
        <select class="form-control" name="products[<?=$key?>][product]" required data-change="hiden-error">
            <option value="">--Seleccionar--</option>
            @if(isset($product->id))
                <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
            @endif
        </select>
    </td>
    <td style="width: 20%;">
        <input class="form-control" type="number"  name="products[<?=$key?>][quantity]" placeholder="Cant." data-keyup="hiden-error" rows="1" value="<?=$product->pivot->quantity ?? '1' ?>">
    </td>
        <td class="remove_row_td" style="width: 10%;">
        <a class="btn btn-outline-danger" data-click="removeProductRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>