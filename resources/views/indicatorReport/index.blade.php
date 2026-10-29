@extends('layouts.main')
@section('title', 'Informe de indicadores')
@section('dashboard', 'true')
@section('select2','true')
@section('charts3','true')
@section('viewjs','indicatorReport/index')
@section('datatables', 'true')
@section('sweetAlert','true')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row card-title">
            <div class="col-md-6">
                <h4 class="card-title top-5" style="margin-top: 27px;">Informe Indicadores comerciales</h4>
                <form id="indicatorMonth" action="" method="POST" class="form-row">
                    {{ method_field('POST') }} 
                    {{ csrf_field() }}
                    <div class="col-4">
                        <input type="month" name="start_date" id="start_date" class="form-control" value="{{Jenssegers\Date\Date::now()->format('Y-m')}}">
                    </div>
                    <div class="col-4">
                        <input type="submit" class="btn btn-primary" value="Filtrar">
                    </div>
                </form>
            </div>
        </div>

        @can('indicator.report.month.index')
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div id="containerCanvas">
                            <canvas style="min-width:100%; min-height:100%" id="myChart" ></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <section class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Comerciales por ventas nuevas</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse">
                            <div class="card-body">

                                <div id="indicatorPerMonth">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @endcan
            
        @can('indicator.report.year.index')
        
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div id="containerCanvasYear">
                            <canvas style="min-width:100%; min-height:100%" id="myCharYear" ></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <section class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Comerciales ventas totales</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse">
                            <div class="card-body">

                                <div id="indicatorPerYear">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endcan
    </div>
    
</div>




@endsection