<tr data-id="<?=$key?>">
    <td style="width: 20%">
        <div>
            <input type="hidden" name="technical[<?=$key?>][id]" value="{{isset($technical->pivot) ?  $technical->pivot->id : ''}}">
            <select class="select2 form-control service" data-change="hiden-error"  name="technical[<?=$key?>][service]">
                <option value="">--Seleccionar--</option>
                @if(isset($technical->id))
                    <option selected value="{{ $technical->id }}">{{ $technical->name }}</option>
                @endif
            </select>
        </div>
    </td>
    <td>
        <div>
            <input type="time" data-keyup="hiden-error" name="technical[<?=$key?>][time]" class="form-control" value="{{ $technical->pivot->time ?? '' }}" placeholder="#">
        </div>
    </td>
    <td style="width: 10%">
        <div>
            <select class="select2 form-control" data-change="hiden-error"  name="technical[<?=$key?>][init_status]" >
                @if(isset($technical->pivot->init_status))
                    <option selected value="{{ $technical->pivot->init_status }}">{{ \App\CrmScheduleStatus::find($technical->pivot->init_status)->name }}</option>                    
                @endif
            </select>
        </div>
    </td>
    <td>
        <div>
            <input type="number" data-keyup="hiden-error" name="technical[<?=$key?>][quantity]" class="form-control" value="{{ $technical->pivot->technical_quantity ?? '' }}" placeholder="#">
        </div>
    </td>
    <td style="width: 25%">
        <div>
            <select class="select2 form-control" data-change="hiden-error"  name="technical[<?=$key?>][user][]" multiple>
                @if(isset($technical->pivot->technicals))
                    @foreach (\App\User::find($technical->pivot->technicals) as  $user)
                        <option selected value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                    
                @endif
            </select>
        </div>
    </td>
    <td>
        <div>
            <input type="date" data-keyup="hiden-error" name="technical[<?=$key?>][start]" class="form-control" value="{{ $technical->pivot->start ?? '' }}" placeholder="#">
        </div>
    </td>
    <td>
        <div>
            <input type="date" data-keyup="hiden-error" name="technical[<?=$key?>][end]" class="form-control" value="{{ $technical->pivot->end ?? '' }}" placeholder="#">
        </div>
    </td>
    <td class="remove_row_td" style="width: 10px;">
    <a class="btn btn-outline-danger" data-click="removeRowTechnical" data-id="<?=$key?>">
        <i class="la la-trash-o"></i>
    </a>
    </td>
</tr>