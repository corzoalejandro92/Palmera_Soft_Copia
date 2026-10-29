@extends('layouts.main') 

@section('title', 'Hoja de llamadas')
@section('viewcss','user/form')
@section('viewjs','scheduling/sheet')
@section('datatables', 'true')
@section('select2','true')
@section('sweetAlert','true')
@section('icheck', 'true')
@section('datepicker', 'true')
    
@php
    use  App\General\DateTranslator;
    $startDate =  isset($startDate)? new Date($startDate) :  new Date(date('Y-m-01'));
    $endDate =    isset($endDate)? new Date($endDate) :  new Date(date('Y-m-t'));   
@endphp

@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Hoja de llamadas</h4>
                    </div>

                </div>

                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <section id="configuration">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card pb-2">
                                            <div class="card-content collapse show">
                                                <div class="card-body card-dashboard">
                                                    <div class="row">
                                                        <div class="col-md-10 offset-md-1">
                                                            <form id="sendScheduleFilter" action="#">
                                                            {{ method_field('POST') }} 
                                                            {{ csrf_field() }}
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5>Filtrar servicios</h5>
                                                                        <select class="form-control select2" data-change="hiden-error" name="services[]" multiple="multiple">
                                                                            @if($services)
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{$service}}" selected>{{\App\Service::find($service)->name}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        <p><small class="text-muted mb-8">Inique al sistema que tipos de servicios desea filtrar en este búsqueda.</small><br></p>
                                                                </div>
                                                                <div class="col-6">
                                                                    <h5>Técnico/Usuario</h5>
                                                                    <fieldset class="form-group position-relative">
                                                                        <select class="form-control select2" data-change="hiden-error" name="users[]" data-placeholder="Técnico" autocomplete="no" multiple>
                                                                        
                                                                        </select>
                                                                    </fieldset>
                                                                </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-3 filterRangeSchedule">
                                                                        <h6>Rango de búsqueda</h6>
                                                                        <fieldset class="form-group position-relative">
                                                                            <input type="text" id="picker_from" class="form-control pickadate-range"  name="start_date" placeholder="Ingrese una fecha" readonly="" value="{{ucwords($startDate->format('d F, Y'))}}">
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-md-3 filterRangeSchedule">
                                                                        <h6>&nbsp;</h6>
                                                                        <fieldset class="form-group position-relative">
                                                                            <input type="text" id="picker_to" class="form-control pickadate-range"  name="end_date" placeholder="Ingrese una fecha" readonly="" value="{{ucwords($endDate->format('d F, Y'))}}">
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-md-3 filterRangeSchedule">
                                                                        <h6>Estados</h6>
                                                                        <fieldset class="form-group position-relative">
                                                                            <select class="form-control select2" data-change="hiden-error" name="statuses[]" multiple="multiple">
                                                                                @if($status)
                                                                                @foreach ($status as $st)
                                                                                    <option value="{{$st}}" selected>{{\App\CrmScheduleStatus::find($st)->name}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                            </select>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <h6>&nbsp;</h6>
                                                                        <button class="btn btn-secondary btn-block">Filtrar</button>
                                                                    </div>
                                                                </div>
                                                                
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div id="list" class="relative" style="min-height: 380px;">
                                                                @include('layouts.loading.full')
                                                            </div>
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

    <script>
        AUTOFILTER =  {{$autofilter ? 1: 0}}
    </script> 

<!--- Incluir modales del módulo -->
@include('scheduling.sub.mdl_large_form')
@include('scheduling.sub.mdl_delete')

@endsection
