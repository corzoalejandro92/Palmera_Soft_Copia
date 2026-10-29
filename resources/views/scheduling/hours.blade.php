@extends('layouts.main') 

@section('title', 'Liquidación de horas')
@section('viewjs','scheduling/hours')
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
                <div class="alert alert-warning"> <b>Funcionalidad experimental.</b> Ten presente, que los tiempo de inicio y finalización de los servicios dependen del uso que el técnico realiza de app de técnicos y que cualquier inconsistencia entre el reporte físico y estos tiempos pueden darsen por múltiples dificultades presentadas en campo. Para los servicios que el técnico no inicie o no finalice, el soft calculará con el tiempo definido en la automatización de la ruta para cada servicio.</div>
            </div>
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Liquidación de horas</h4>
                    </div>

                </div>

                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <section id="configuration">
                                <div class="row">
                                    <div class="col-md-10 offset-md-1">
                                        <form id="sendScheduleFilterH" action="#">
                                        {{ method_field('POST') }} 
                                        {{ csrf_field() }}
                                        <div class="row">
                                                <div class="col-4">
                                                    <h5>Técnico/Usuario</h5>
                                                    
                                                        <select class="form-control select2" data-change="hiden-error" name="users[]" data-placeholder="Técnico" autocomplete="no" multiple>
                                                            @if($user)
                                                                <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                                            @endif
                                                        </select>
                                                </div>
                                                <div class="col-3 filterRangeSchedule">
                                                    <h6>Fecha</h6>
                                                    <fieldset class="form-group position-relative">
                                                        <input type="date"  class="form-control"  name="start_date" placeholder="Ingrese una fecha" value="{{ $date ?  \Carbon\Carbon::parse($date)->format('Y-m-d') :$startDate->format('Y-m-d')}}">
                                                    </fieldset>
                                                </div>
                                                   
                                                    <div class="col-5">
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
