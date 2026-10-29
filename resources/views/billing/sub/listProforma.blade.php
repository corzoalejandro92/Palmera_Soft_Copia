<?php use Carbon\Carbon; ?>
<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th></th>
            <th class="text-center">Fecha de servicio</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Servicio</th>
            <th class="text-center">Precio</th>
            <th class="text-center">Día de corte</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($schedules as $schedule)
            <tr>
                <td style="width: 5px;">
                    <div class="row icheck_minimal skin" style="margin-left: auto;margin-right: auto;">
                        <div>
                            <fieldset>
                                <input type="checkbox" name="received" value="{{$schedule->id}}">
                            </fieldset>
                        </div>
                    </div>
                </td>
                <td class="text-center">{{ Carbon::parse($schedule->date)->format("Y-m-d") }}</td>
                <td>
                    {{$schedule->crm->name }} <br>
                    {{$schedule->office->name }}
                
                </td>
                <td>{{$schedule->service->name}}</td>
                <td> $ {{ number_format($schedule->price,0,'.',',') }}</td>
                <td class="text-center">{{ $schedule->crm->billing_cycle}}</td>
            </tr>
        @endforeach
    </tbody>

</table>