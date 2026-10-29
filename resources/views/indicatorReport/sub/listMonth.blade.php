
@php setlocale(LC_ALL, 'es_ES'); 
    use App\General\Menu;
@endphp
<div class="row">
    <div class="col-md-12 col-lg-12">

        <!-- INICIO Cotizaciones realizadas por comercial -->
        <div class="card">
            <div class="card-header p-1">
                <h4 class="card-title float-left">Ventas nuevas por comercial para el mes de {{Jenssegers\Date\Date::parse($start->format('Y-m-d'))->format('F')}}</h4>
            </div>
            <div  class="col-md-12 col-lg-12">
                @if($users && $users->count() > 0)
                    <table class="table table-sm" >
                        <thead>
                            <tr>
                                <th class="border-top-0">#</th>
                                <th class="border-top-0">Nombre</th>
                                <th class="border-top-0">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $cont=1;
                                $users_color[]="#35977a";
                                foreach ($users as $user):
                                $qta_approved = 0;
                                ?>
                                @php 
                                    $qta_approved=$user->sales->where('new_service',1)->whereBetween('invoice_date', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')])->pluck('value')->sum();
                                
                                    $users_color[] = Menu::randomColor();
                                    $users_name2[$user->id] = strtoupper($user->name);
                                    $users_approved2[$user->id] = $qta_approved;
                                
                                @endphp
                                    
                                
                            <?php
                                endforeach;
                                arsort($users_approved2);
                            
                            $users_name3[] = "META";
                            $users_approved3[] = isset($new_sales_objetive) ? $new_sales_objetive->value : 0 ;
                            ?>
                            @foreach ($users_approved2 as $key => $value)
                                    @if ($loop->first)
                                        <tr>
                                            <td class="text-truncate align-middle">0</td>
                                            <td class="text-truncate align-middle">{{$users_name3[0]}} </td>
                                        
                                            <td class="text-truncate align-middle"><b> $ {{  number_format($users_approved3[0],0,'.',',') }}</b>
                                            </td>
                                            
                                        </tr>
                                    @endif

                                    <tr>
                                        <td class="text-truncate align-middle">{{$cont++}}</td>
                                        <td class="text-truncate align-middle">{{$users_name2[$key]}}</td>
                                    
                                        <td class="text-truncate align-middle"><b> $ {{  number_format($value,0,'.',',') }}</b>
                                        </td>
                                        
                                    </tr>
                                    <?php
                                        $users_approved3[]=$value;
                                        $users_name3[]=$users_name2[$key];
                                    ?>
                            @endforeach
                            <input type="hidden" id="month_new" value='<?=Jenssegers\Date\Date::parse($start->format('Y-m-d'))->format('F')?>'>
                            <input type="hidden" id="users_name" value='<?=json_encode($users_name3)?>'>
                            <input type="hidden" id="users_approved" value='<?= json_encode($users_approved3)?>'>
                            <input type="hidden" id="users_color" value='<?= json_encode($users_color)?>'>
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-danger mb-2" role="alert">
                        <strong>Sin datos aún!</strong>  Al parecer ventas registradas.
                    </div>
                    @endif
            </div>
            
        </div>
        <!-- FIN Cotizaciones realizadas por comercial -->
    </div>
    
</div>







