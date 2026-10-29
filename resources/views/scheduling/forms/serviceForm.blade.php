<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;

?>
<h4>Datos del Servicio</h4>
<hr>
<div class="form-group row">
    <div class="col-4">
        <label class="col-form-label text-bold-700">Cliente</label>
        <h5>{{$scheduling->crm->name}}</h5>
    </div>
    <div class="col-4">
        <label class="col-form-label text-bold-700">Sucursal</label>
        <h5>{{$scheduling->office->name}}</h5>
        <h5>{{$scheduling->office->address}}</h5>
        <h5>{{$scheduling->office->city->name}}, {{$scheduling->office->city->state->name}} </h5>
    </div>
    <div class="col-4">
        <label class="col-form-label text-bold-700">Contacto</label>
        <h5>Sin especificar</h5>
    </div>

</div>
<hr>

<div class="form-group row">
    <div class="col-4">
        <label class="col-form-label text-bold-700">Control</label>
        <h5>{{$scheduling->crmScheduleControl->infestation->name}}</h5>
    </div>
    <div class="col-4">
        <label class="col-form-label text-bold-700">Fecha en el cronograma</label>
        @php 
            $date  =  Carbon::parse($scheduling->crmScheduleControl->date); 
            $start =  $date->copy()->startOfWeek()->format('d');
            $end   =  $date->copy()->endOfWeek()->format('d');
            $titleDate =  Str::limit(Str::ucfirst($date->copy()->startOfWeek()->monthName), 3,''). " ". $start ." a " .Str::limit(Str::ucfirst($date->copy()->endOfWeek()->monthName), 3,''). " ". $end;
        @endphp
        <h5>{{$titleDate}}</h5>
    </div>
</div>
<hr>



