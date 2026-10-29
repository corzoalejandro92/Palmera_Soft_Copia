

@extends('layouts.main')
@section('title', 'Reporte Notas de factura')
@section('viewjs', 'billing/evsf')
@section('datatables', 'true')
@section('select2','true')
@section('tooltip','true')
@section('sweetAlert','true')

@php
    use  App\General\DateTranslator;
    $startDate =  new Date(date('Y-m-01'));
    $endDate =  new Date(Carbon\Carbon::now()->format('Y-m-t'));   
@endphp


@section('content')

    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Reporte Notas de factura</h4>
                    </div>
                    {{-- <div class="col-md-6" align="right">
                        <button class="btn btn-outline-teal" data-click="openForm" data-id>
                            <i class="ft-plus"></i>
                            Crear Proforma
                        </button>
                    </div> --}}
                </div>
    
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <section id="configuration">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card" >
                                                <div class="card-header">
                                                    <h4 class="card-title">Filtrar información</h4>
                                                    <a class="heading-elements-toggle">
                                                        <i class="la la-ellipsis font-medium-3"></i>
                                                    </a>
                                                    <div class="heading-elements">
                                                        <ul class="list-inline mb-0">
                                                            <li>
                                                                <a data-action="collapse">
                                                                    <i class="ft-minus"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a data-action="reload">
                                                                    <i class="ft-rotate-cw"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a data-action="expand">
                                                                    <i class="ft-maximize"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a data-action="close">
                                                                    <i class="ft-x"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-content collapse show">
                                                    <div class="card-body">
                                                        <form id="sendScheduleFilter" action="#">
                                                        <div class="row">
                                                           
                                                                {{ method_field('POST') }} 
                                                                {{ csrf_field() }}
                                                                <div class="col-md-3 filterRangeSchedule">
                                                                    <h6>Rango de búsqueda</h6>
                                                                    <fieldset class="form-group position-relative">
                                                                        <input type="date"  class="form-control pickadate-range"  name="start_date" placeholder="Ingrese una fecha" value="{{ucwords($startDate->format('Y-m-d'))}}">
                                                                    </fieldset>
                                                                </div>
                                                                
                                                                <div class="col-md-3 filterRangeSchedule">
                                                                    <h6>&nbsp;</h6>
                                                                    <fieldset class="form-group position-relative">
                                                                        <input type="date"  class="form-control pickadate-range"  name="end_date" placeholder="Ingrese una fecha" value="{{ucwords($endDate->format('Y-m-d'))}}">
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h6>&nbsp;</h6>
                                                                    <button class="btn btn-secondary btn-block">Filtrar</button>
                                                                </div>
                                                            
                                                        </div>
                                                    </form>


                                                        <div class="list-group">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<!--- Incluir modales del módulo -->
@include('billing.sub.mdl_form')

@endsection
