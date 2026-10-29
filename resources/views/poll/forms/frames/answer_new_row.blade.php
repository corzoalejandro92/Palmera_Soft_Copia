
<tr data-id="<?=$skey?>">
    @if(isset($answer->id))
        <input type="hidden" name="question[<?=$key?>][answer][{{$skey}}][id]" value="{{$answer->id}}">
    @endif
    <td>
    <input class="form-control" type="text" name="question[<?=$key?>][answer][{{$skey}}][answer]" data-keyup="hiden-error" placeholder="Ingresa la Respuesta" value="{{isset($answer->answer) ? $answer->answer : ''}}">
    </td>
    <td class="add_row_th" colspan="2">
    <a class="btn btn-outline-teal" data-key="{{$key}}"  data-click="addRowAnswer" >
            <i class="la la-plus-circle"></i>
        </a>
        <a class="btn btn-outline-danger" data-click="removeAnswerRow" data-key="<?=$key?>" data-skey="{{$skey}}">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>