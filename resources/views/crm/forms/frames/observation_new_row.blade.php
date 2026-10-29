<tr data-id="<?=$key?>" >
    <td class="text-center" style="width: 10%">
        <div>
            <input type="hidden" name="observation[<?=$key?>][id]" value="{{isset($observation->id) ?  $observation->id : ''}}">
            <label style="margin-top: 10px; {{ isset($observation->user_id) && $observation->user->deleted_at ? 'text-decoration: line-through;': '' }}">{{isset($observation->user->name) ?  $observation->user->name : Auth::User()->name}}</label>
            <input type="hidden" readonly  name="observation[<?=$key?>][user_id]" value="{{isset($observation->user_id) ?  $observation->user_id : Auth::User()->id}}">
        </div>
    </td>
    <td style="width: 30%">
        <textarea {{ (isset($observation->user_id)  && $observation->user_id == Auth::User()->id) || !isset($observation->user_id)   ? '' : 'readonly'}} class="form-control" name="observation[<?=$key?>][observation]">{{$observation->observation ?? ''}}</textarea>
    </td>

    <td class="text-center" style="width: 10%">
        <div class="form-check">
            <input {{isset($observation->private) && $observation->private ? 'checked': ''}}  class="form-check-input" type="checkbox" value=1 id="flexCheckDefault<?=$key?>" name="observation[<?=$key?>][private]">
            <label class="form-check-label" for="flexCheckDefault<?=$key?>">
              Nota privada
            </label>
          </div>
    </td>
    
   
    <td class="text-center" style="width: 10%">
        <label style="margin-top: 10px;">{{ isset($observation->created_at) ?  $observation->created_at : date('Y-m-d') }}</label>
    </td>

    <td style="width: 30%">
        <textarea class="form-control" name="observation[<?=$key?>][feedback]">{{$observation->feedback ?? ''}}</textarea>
    </td>

    
 

    <td class="text-center" style="width: 10%" >
        <label style="margin-top: 10px;">{{ isset($observation->feedback_at) ?  $observation->feedback_at : date('Y-m-d') }}</label>
    </td>
    
        @if((isset($observation->user_id)  && $observation->user_id == Auth::User()->id)  || !isset($observation->user_id))
            <td class="remove_row_td" style="width: 10px;">
            <a class="btn btn-outline-danger" data-click="removeRowObservation" data-key="{{$key}}">
                <i class="la la-trash-o"></i>
            </a>
        @endif
    </td>
    
</tr>