<tr data-id="<?=$key?>">
    <td>
        @if($workingHour)
            <input type="hidden" name="workingHour[<?=$key?>][id]" value="{{$workingHour->id ?? ''}}" />
        @endif 
        <div align="center" ><i class="la la-clock-o" style="font-size: 2.4rem;font-weight: 800;"></i></div>
        
    </td>
    <td>
        <input type="text"  name="workingHour[<?=$key?>][start]" value="{{$workingHour->start ?? ''}}" class="timepicker-start form-control" data-keyup="hiden-error">
    </td>
    <td>
        <input type="text"  name="workingHour[<?=$key?>][end]" value="{{$workingHour->end ?? ''}}" class="timepicker-end form-control" data-keyup="hiden-error">
    </td>
    <td>
     
        <input type="checkbox" class="form-control form-check" value="1" name="workingHour[<?=$key?>][extra]"  {{$workingHour && $workingHour->extra ? 'checked':''}}>
              
    </td>
    <td class="remove_row_td" style="width: 10px;">
    <a class="btn btn-outline-danger" data-click="removeRow" data-id="<?=$key?>">
        <i class="la la-trash-o"></i>
    </a>
    </td>
</tr>