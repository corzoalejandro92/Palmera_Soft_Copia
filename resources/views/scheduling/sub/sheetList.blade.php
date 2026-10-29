<?php 
use Carbon\Carbon;
use Illuminate\Support\Str;
?>
<form action="venta.php" method="post" id="form">
    <table class="table table-striped table-bordered zero-configuration" id="tblList" >
        <thead>
            <tr>
                <th></th>
                <th>Fecha</th>
                <th class="text-center">Servicios</th>
                <th class="text-center">Técnico(s)</th>
                <th class="text-center">Cliente</th>
                <th class="text-center">Segumientos</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Notas externas</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            @php 
                if(isset($visual_type) && $visual_type == 'day'){
                    $date  =  Carbon::parse($schedule->date); 
                    $start =  $date->copy()->startOfWeek()->format('d');
                    $end   =  $date->copy()->endOfWeek()->format('d');
                    $title_date =  Str::ucfirst($date->copy()->startOfWeek()->monthName). " ". $start ." a " .Str::ucfirst($date->copy()->endOfWeek()->monthName). " ". $end;
                }else{
                    $date  =  Carbon::parse($schedule->date); 
                    $start =  $date->copy()->format('d');
                    $title_date =  Str::ucfirst($date->copy()->monthName). " ". $start ." de ".$date->format('Y');
                }

                // if($schedule->infestation->monthly){
                //     $date  =  Carbon::parse($schedule->date); 
                //     $start =  $date->copy()->format('d');
                //     $title_date =  Str::ucfirst($date->copy()->monthName);
                // }
                
            @endphp
                @include('scheduling.sub.frames.sheet_list_tr')
            @endforeach
            
        </tbody>
    </table>
</form>
