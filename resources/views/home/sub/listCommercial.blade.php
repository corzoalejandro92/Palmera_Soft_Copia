
@php setlocale(LC_ALL, 'es_ES'); @endphp
<div class="row">
    <div class="col-md-12 col-lg-12">

        <!-- INICIO Cotizaciones realizadas por comercial -->
        <div class="card">
            <div class="card-header p-1">
                <h4 class="card-title float-left">Cotizaciones realizadas por comercial para el mes de {{Jenssegers\Date\Date::parse($start->format('Y-m-d'))->format('F')}}</h4>
            </div>
            <div  class="col-md-12 col-lg-12">
                @if($users && $users->count() > 0)
                    <table class="table table-padded table-xl mb-0" id="recent-project-table">
                        <thead>
                            <tr>
                                <th class="border-top-0">Nombre</th>
                                <th class="border-top-0">Cotizaciones</th>
                                <th class="border-top-0">Aprobadas</th>
                                <th class="border-top-0">Pendientes</th>
                                <th class="border-top-0">Visitas<br>seguimientos</th>
                                <th class="border-top-0">Visitas<br>comerciales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $qt_approved=0;
                                $qt_pending=0;
                                $qt_total=0;
                                foreach ($users as $user):?>
                            <tr>
                                <td class="text-truncate align-middle">
                                    <div class="row">
                                        <div class="col-3">
                                            <img class="avatar xxs mr-1" src="<?= url($user->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                                        </div>
                                        <div class="col-9">
                                            {{$user->name}} 
                                        </div>
                                    </div>
                                </td>
                                @php 

                                $aqu_approved = $user->quotes->whereIn('status_id',[2,4,5,6])->whereBetween('approved_at', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')]);
                                    if($aqu_approved){
                                        $qta_approved = $aqu_approved->pluck('services')->flatten()->where('approved',1)->sum('price');
                                        $qt_approved += $qta_approved; // sumatoria de todas las  cotizaciones aprobadas
                                        $quotes_approved_count = $aqu_approved->count();  //contador de las cotizaciones aprobadas
                                    }else{
                                        $qta_approved = 0;
                                    }

                                $aqu_pending = $user->quotes->whereIn('status_id',[1,3])->whereBetween('created_at', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')]);
                                    if($aqu_pending){
                                        $qta_pending = $aqu_pending->pluck('services')->flatten()->where('approved',0)->sum('price');
                                        $qt_pending += $qta_pending; //sumatoria de todas las cotizaciones pendientes
                                        $quotes_pending_count = $aqu_pending->count(); //contador de las cotizaciones pendientes
                                    }else{
                                        $qta_pending = 0;
                                    }

                                $aquote_total = $user->quotes->whereBetween('created_at', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')]);
                                    if($aquote_total){
                                        $qta_total = $aquote_total->pluck('services')->flatten()->sum('price');
                                        $qt_total += $qta_total; //sumatoria de todas las cotizaciones totales
                                        $quotes_total_count = $aquote_total->count(); //contador de las cotizaciones tatales
                                    }else{
                                        $qta_total = 0;
                                    }

                                @endphp
                                <td class="text-truncate align-middle"><b> ({{ $quotes_total_count}}) $ {{  number_format($qta_total,0,'.',',') }}</b>
                                </td>
                                <td class="text-truncate align-middle"><b> ({{ $quotes_approved_count}}) $ {{  number_format($qta_approved,0,'.',',') }}</b>
                                </td>
                                <td class="text-truncate align-middle"><b>({{ $quotes_pending_count }}) $ {{  number_format($qta_pending,0,'.',',') }}</b>
                                </td>
                                <td class="text-truncate align-middle"><b>{{ $user->follow_visits }}</b>
                                </td>
                                <td class="text-truncate align-middle"><b>{{ $user->commercials_visits }}</b>
                                </td>

                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-danger mb-2" role="alert">
                        <strong>Sin datos aún!</strong>  Al parecer aun no hay cotizaciones creadas en esta sede.
                    </div>
                    @endif
            </div>
        </div>
        <!-- FIN Cotizaciones realizadas por comercial -->

        <!-- INICIO indicadores de ingreso de cotizaciones -->
        <div class="card">
            <div class="card-header p-1">
                <h4 class="card-title float-left">Indicadores de flujo de cotizaciones</h4>
            </div>
            <div class="row">
                 <div class="col-xl-4 col-lg-12">
                    <div class="card ">
                        <div class="card-header bg-hexagons">
                            <h4 class="card-title ">Mes de {{Jenssegers\Date\Date::parse($start->format('Y-m-d'))->format('F')}}</h4>
                            <div class="heading-elements">
                                <ul class="list-inline d-block mb-0">
                                    <li>
                                        <a class="btn btn-sm btn-success box-shadow-3 round btn-min-width pull-right" href="#" target="_blank">
                                            <span class="white">Total</span>
                                            <i class="ft-bar-chart pl-1 white"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show bg-hexagons">
                            <div class="card-body pt-0 pb-1">
                                <div class="media d-flex">
                                    <div id="new-customers" class="height-100 newProjectsdonutShadow">
                                        <svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-donut" style="width: 100%; height: 100%;">
                                            <g class="ct-series ct-series-a"><path d="M49.992,5A45,45,0,1,0,50,5L50,10A40,40,0,1,1,49.993,10Z" class="ct-slice-donut-solid" ct:value="100">
                                                </path></g><g><text  dx="50" dy="54.751953125" text-anchor="middle" class="ct-label" font-family="feather"></text></g><defs>
                                                    <linearGradient id="donutGradient5" x1="0" y1="1" x2="0" y2="0"><stop offset="0%" stop-color="rgba(80,152,136,1)"></stop><stop offset="95%" stop-color="rgba(80,152,136, 0.3)"></stop></linearGradient></defs></svg></div>
                                    <div class="media-body text-right mt-2">
                                        @php 
                                            $quote_total = isset($qt_total) ? $qt_total : 0; 
                                        @endphp
                                        <h3 class=" font-large-2 blue-grey lighten-1 ">${{  number_format($quote_total,0,'.',',')}}
                                        </h3>
                                        <h6 class="mt-1">
                                            <span class="text-muted">Dinero de cotizaciones realizadas</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="card ">
                        <div class="card-header bg-hexagons">
                            <h4 class="card-title ">Mes de {{Jenssegers\Date\Date::parse($start->format('Y-m-d'))->format('F')}}</h4>
                            <div class="heading-elements">
                                <ul class="list-inline d-block mb-0">
                                    <li>
                                        <a class="btn btn-sm btn-success box-shadow-3 round btn-min-width pull-right" href="#" target="_blank">
                                            <span class="white">Aprobado</span>
                                            <i class="ft-bar-chart pl-1 white"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show bg-hexagons">
                            <div class="card-body pt-0 pb-1">
                                <div class="media d-flex">
                                    <div class="align-self-center width-100">
                                        <div id="new-customers" class="height-100 newProjectsdonutShadow">
                                            <svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-donut" style="width: 100%; height: 100%;">
                                                <g class="ct-series ct-series-a"><path d="M49.992,5A45,45,0,1,0,50,5L50,10A40,40,0,1,1,49.993,10Z" class="ct-slice-donut-solid" ct:value="100">
                                                    </path></g><g><text  dx="50" dy="54.751953125" text-anchor="middle" class="ct-label" font-family="feather"></text></g><defs>
                                                        <linearGradient id="donutGradient5" x1="0" y1="1" x2="0" y2="0"><stop offset="0%" stop-color="rgba(80,152,136,1)"></stop><stop offset="95%" stop-color="rgba(80,152,136, 0.3)"></stop></linearGradient></defs></svg></div>
                                    </div>
                                    <div class="media-body text-right mt-2">
                                        <h3 class=" font-large-2 blue-grey lighten-1 ">
                                            @php
                                                $qt=isset($qt_approved) ? $qt_approved : 0 ; 
                                            @endphp
                                            ${{  number_format($qt,0,'.',',')}}

                                        </h3>
                                        <h6 class="mt-1">
                                            <span class="text-muted">Dinero de cotizaciones en estado aprobado, cronograma generado, programacion generada o facturada</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>

                <div class="col-xl-4 col-lg-12">
                    <div class="card ">
                        <div class="card-header bg-hexagons">
                            <h4 class="card-title ">Al mes de {{Jenssegers\Date\Date::parse($start->format('Y-m-d'))->format('F')}}</h4>
                            <div class="heading-elements">
                                <ul class="list-inline d-block mb-0">
                                    <li>
                                        <a class="btn btn-sm btn-warning box-shadow-3 round btn-min-width pull-right" href="#" target="_blank">
                                            <span class="white">Pendiente</span>
                                            <i class="ft-bar-chart pl-1 white"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show bg-hexagons">
                            <div class="card-body pt-0 pb-1">
                                <div class="media d-flex">
                                    <div class="align-self-center width-100">
                                        <div id="tasks-completed" class="height-100 donutShadow">
                                            <svg xmlns:ct="http://gionkunz.github.com/chartist-js/ct" width="100%" height="100%" class="ct-chart-donut" style="width: 100%; height: 100%;">
                                                <g class="ct-series ct-series-a"><path d="M49.992,5A45,45,0,1,0,50,5L50,10A40,40,0,1,1,49.993,10Z" class="ct-slice-donut-solid" ct:value="100">
                                                    </path></g><g><text dx="50" dy="54.751953125" text-anchor="middle" class="ct-label" font-family="feather"></text></g><defs>
                                                        <linearGradient id="donutGradient7" x1="0" y1="1" x2="0" y2="0"><stop offset="0%" stop-color="rgba(253,193,7,1)"></stop><stop offset="95%" stop-color="rgba(253,193,7, 0.3)"></stop></linearGradient></defs></svg></div>
                                    </div>
                                    <div class="media-body text-right mt-2">
                                        @php 
                                            $quote_pending = isset($qt_pending) ? $qt_pending : 0;
                                        @endphp
                                        <h3 class=" font-large-2 blue-grey lighten-1 ">${{number_format($quote_pending,0,'.',',')}}
                                        </h3>
                                        <h6 class="mt-1">
                                            <span class="text-muted">Dinero de cotizaciones en estado pendiente<br><br></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>

               
            </div>
        </div>
        <!-- FIN indicadore de ingreso de cotizaciones -->

    </div>
</div>







