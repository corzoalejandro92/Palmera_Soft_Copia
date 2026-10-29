@extends('layouts.main') 

@section('title', 'Listado de Auditorias')
@section('viewjs','audit/audit')
@section('viewcss', 'audit/show')
@section('datatables', 'true')
@section('select2','true')
@section('sweetAlert','true')
@section('fileInput','true')
@section('ckeditor', 'true')
@section('tagsinput', 'true')
@section('datepicker', 'true')
@section('timepicker','true')
@section('vistimeline','true')
@section('fullcalendar','true')
@section('map','true')


@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Listado de Auditorias</h4>
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
                                                        <div class="col-sm-12 col-md-2">
                                                            <div class="dataTables_length" id="tblList_length">
                                                                <h5>Mostrar</h5>
                                                                    <select style="position: relative;top: -10px;" name="tblList_length" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="10" {{$audits->perpage() == 10 ? 'selected' : ''}} >10 registros</option>
                                                                        <option value="25" {{$audits->perpage() == 25 ? 'selected' : ''}} >25 registros</option>
                                                                        <option value="50" {{$audits->perpage() == 50 ? 'selected' : ''}} >50 registros</option>
                                                                        <option value="100" {{$audits->perpage() == 100 ? 'selected' : ''}} >100 registros</option>
                                                                    </select> 
                                                                    
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-12 col-md-2">
                                                            <div class="dataTables_length" id="tblList_headquarter">
                                                                <h5>Sede</h5>
                                                                    <select style="position: relative;top: -10px;" name="tblList_headquarter" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="" selected disabled>Sede</option>
                                                                        @foreach ($headquarters as $headquarterr)
                                                                            @php
                                                                                $check=$headquarterr->id == $headquarter ? 'selected' : '';
                                                                            @endphp
                                                                            <option value="{{$headquarterr->id}}" {{$check}}>{{$headquarterr->name}}</option>
                                                                        @endforeach
                                                                    </select> 
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-2">
                                                            <div class="dataTables_length" id="tblList_model">
                                                                <h5>Modulo</h5>
                                                                    <select style="position: relative;top: -10px;" name="tblList_model" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="" selected >Modulo</option>
                                                                        @foreach ($models as $modell)
                                                                            @php
                                                                                $check=$modell->auditable_type==$model ? 'selected' : '';
                                                                            @endphp
                                                                            <option value="{{$modell->auditable_type}}" {{$check}}>{{$modell->model_name}}</option>
                                                                        @endforeach
                                                                    </select> 
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-2">
                                                            <div class="dataTables_length" id="tblList_action">
                                                                <h5>Acción</h5>
                                                                    <select style="position: relative;top: -10px;" name="tblList_action" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="" selected >Acción</option>
                                                                        @foreach ($events as $event)
                                                                            @php
                                                                                $check=$event->event==$event ? 'selected' : '';
                                                                            @endphp
                                                                            <option value="{{$event->event}}" {{$check}}>{{$event->eventt}}</option>
                                                                        @endforeach
                                                                    </select> 
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <h5 style="margin:0">Usuarios</h5>
                                                                <select class="form-control select2" style="position: relative;" data-change="hiden-error"  name="users[]" data-placeholder="Usuario" autocomplete="no" multiple>
                                                                    @if($users)
                                                                        <option value="{{$users->id}}" selected>{{$users->name}}</option>
                                                                    @endif
                                                                </select>
                                                        </div>
                                                        
                                                        
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6">
                                                            <h6>Fecha Inicio</h6>
                                                            <fieldset class="form-group position-relative" style="display: flex;">
                                                                <input type="date"  class="form-control" id="startdate" name="start_date" placeholder="Ingrese una fecha" value="{{ $date ?  \Carbon\Carbon::parse($date)->format('Y-m-d') :''}}"><button id='startdatebtn' onclick="javascript:startdate.value=''">X</button>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <h6>
                                                                Fecha Fin
                                                            </h6>
                                                            <fieldset class="form-group position-relative" style="display: flex;">
                                                                <input type="date" class="form-control" id="enddate" name="end_date" placeholder="Ingrese una fecha" value="{{ $date2 ?  \Carbon\Carbon::parse($date2)->format('Y-m-d') :''}}"><button id='enddatebtn' onclick="javascript:enddate.value=''">X</button>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <h5 style="display: inline-flex;">Buscar</h5>
                                                            <input  name="search" class="form-control" placeholder="Titulo" aria-controls="tblList" value="{{$term ?? ''}}">
                                                        
                                                    </div>
                                                    <div id="list"  class="relative" style="min-height: 380px;">
                                                        @include('auditing.sub.list')
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


@include('auditing.sub.mdl_form')


@endsection
