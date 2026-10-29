@extends('layouts.main')
@section('title', 'Clientes')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('fileInput', 'true')
@section('viewcss','crm/show')
@section('viewjs','crm/expiredNotify')


@section('content')



<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    @php
                        \Carbon\Carbon::setLocale(config('app.locale'));
                        setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
                        $dateT =  \Carbon\Carbon::parse($notification->created_at)->isoFormat('MMMM Do \d\e\l YYYY');
                    @endphp
                    <h4 class="card-title top-5">Lista de sucursales {{ $notification->data['headquarter']['name'] }} sin cronograma para ejecutar de {{ $dateT }}</h4>
                </div>
           
                <div class="col-md-12">&nbsp;</div>
                <div class="col-md-12" style="margin-bottom: 10px;">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <table class="table table-striped table-bordered zero-configuration" id="tblList">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cliente</th>
                                            <th class="text-center" style="width:300px;">Sucursal</th>
                                            <th class="text-center" style="width:300px;">Dirección</th>
                                            <th class="text-center">Ultimo servicio</th>
                                            <th class="text-center">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                        @foreach ($notification->data['offices'] as $office)
                                        @php
                                            \Carbon\Carbon::setLocale(config('app.locale'));
                                            setlocale(LC_ALL, 'es_MX', 'es', 'ES', 'es_MX.utf8');
                                            $date =  \Carbon\Carbon::parse($office['last_service']['date'])->isoFormat('MMM Do [de] YYYY');
                                        @endphp
                                            <tr>
                                                <td><b>{{ $office['crm']['name'] }}</b></td>
                                                <td> {{ $office['name'] }}</td>
                                                <td> {{ $office['full_address'] }}</td>
                                                <td> {{ $office['last_service']['service']['name'] }}</td>
                                                <td> {{ $date }}</td>
                                            </tr>
                                        @endforeach
                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

               
            </div>
        </div>
    </section>


<!--- Incluir modales del módulo -->

@endsection
<script type="text/javascript">
    window.document.title = 'Lista de sucursales {{ $notification->data['headquarter']['name'] }} sin cronograma para ejecutar de {{ $dateT }}';
</script>
