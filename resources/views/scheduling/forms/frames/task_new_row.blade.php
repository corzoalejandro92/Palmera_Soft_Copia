<tr data-id="<?=$key?>">

        @if($task)
            <input type="hidden" name="tasks[<?=$key?>][id]" value="{{$task->id ?? ''}}" />
        @endif 
    <td>
        <span class="d-number"></span>
    </td>

    <td>
        <textarea class="form-control"  name="tasks[<?=$key?>][description]" placeholder="Descripción" data-keyup="hiden-error" rows="1"><?=$task->description ?? '' ?></textarea>
    </td>
    <td class="remove_row_td" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeTaskRow" data-id="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>