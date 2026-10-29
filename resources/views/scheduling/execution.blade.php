@extends('layouts.main') 

@section('title', 'Ejecución de Rutas')
@section('viewjs','scheduling/execution')
@section('viewcss', 'crm/show')
@section('datatables', 'true')
@section('datatableExport', 'true')
@section('select2','true')
@section('sweetAlert','true')
@section('datepicker', 'true')
@section('timepicker','true')
@section('icheck', 'true')

@php
    use  App\General\DateTranslator;
    $startDate =  new Date(date('Y-m-d'));
    $endDate =  new Date(Carbon\Carbon::now()->format('Y-m-t'));   
@endphp


@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Ejecución de rutas</h4>
                    </div>

                </div>

                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <section id="configuration">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <form id="sendScheduleFilterP" action="#">
                                        {{ method_field('POST') }} 
                                        {{ csrf_field() }}
                                        <div class="row">
                                            
                                                {{-- <div class="col-md-6">
                                                    <h5>Filtrar servicios</h5>
                                                        <select class="form-control select2" data-change="hiden-error" name="services[]" multiple="multiple"></select>
                                                        <p><small class="text-muted mb-8">Inique al sistema que tipos de servicios desea filtrar en este búsqueda.</small><br></p>
                                                </div> --}}
                                                
                                                <div class="col-6">
                                                    <h5>Técnico/Usuario</h5>
                                                    
                                                        <select class="form-control select2" data-change="hiden-error" name="users[]" data-placeholder="Técnico" autocomplete="no" multiple>
                                                            @if($user)
                                                                <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                                            @endif
                                                        </select>
                                                </div>
                                                <div class="col-3 filterRangeSchedule">
                                                    <h6>Fecha Inicio</h6>
                                                    <fieldset class="form-group position-relative">
                                                        <input type="date"  class="form-control"  name="start_date" placeholder="Ingrese una fecha" value="{{ $date ?  \Carbon\Carbon::parse($date)->format('Y-m-d') :$startDate->format('Y-m-d')}}">
                                                    </fieldset>
                                                </div>
                                                <div class="col-3 filterRangeSchedule">
                                                    <h6>
                                                        @new(['date' => '2021-10-04', 'title' => 'Fecha Fin' ,'text' => 'Campo opcional, ahora podrás filtrar por un rango mas amplio de servicios, si dejas este campo en blanco solo se filtrará por la fecha inicio.']) 
                                                        Fecha Fin
                                                    </h6>
                                                    <fieldset class="form-group position-relative" style="display: flex;">
                                                        <input type="date" id="enddate" class="form-control"  name="end_date" placeholder="Ingrese una fecha" value="{{ $date2 ?  \Carbon\Carbon::parse($date2)->format('Y-m-d') :''}}"><button onclick="javascript:enddate.value=''">X</button>
                                                    </fieldset>
                                                </div>
                                                    <div class="col-6">
                                                        <h5>Cliente</h5>
                                                        <select class="form-control select2" data-change="hiden-error" id="customers" name="customers[]" data-placeholder="Técnico" autocomplete="no" multiple>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-3" style="padding-top: 12px;">
                                                        <h5>Revisado</h5>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="reviewed2" checked name="reviewed" value="" class="custom-control-input">
                                                            <label class="custom-control-label" for="reviewed2">Cualquiera</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="reviewed1" name="reviewed" value="1" class="custom-control-input">
                                                            <label class="custom-control-label" for="reviewed1">Si</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="reviewed0" name="reviewed" value="0" class="custom-control-input">
                                                            <label class="custom-control-label" for="reviewed0">No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h6>&nbsp;</h6>
                                                        <button class="btn btn-secondary btn-block">Filtrar</button>
                                                    </div>
                                                
                                                <div class="col-md-12">
                                                    <hr>
                                                </div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card pb-2">
                                            <div class="card-content collapse show">
                                                <div class="card-body card-dashboard">
                                                    <div id="list" class="relative" style="min-height: 380px;">
                                                        @include('layouts.loading.full')
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

@include('scheduling.sub.mdl_large_form')
@include('scheduling.sub.mdl_form')
@include('scheduling.sub.mdl_delete')

@include('poll.sub.mdl_showResults')
<script>
    AUTOFILTER =  {{$user ? 1: 0}}
</script> 

@endsection
