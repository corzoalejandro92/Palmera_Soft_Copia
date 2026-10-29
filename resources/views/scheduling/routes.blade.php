@extends('layouts.main') 

@section('title', 'Listado de Rutas')
@section('viewjs','scheduling/route')
@section('viewjs2','scheduling/common')
@section('viewcss', 'crm/show')
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
                        <h4 class="card-title top-5">Listado de Rutas</h4>
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
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="dataTables_length" id="tblList_length">
                                                                <label style="display: inline-flex;">Mostrar 
                                                                    <select style="position: relative;top: -10px;margin-left: 5px;margin-right: 5px;" name="tblList_length" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="10" {{$routes->perpage() == 10 ? 'selected' : ''}} >10</option>
                                                                        <option value="25" {{$routes->perpage() == 25 ? 'selected' : ''}} >25</option>
                                                                        <option value="50" {{$routes->perpage() == 50 ? 'selected' : ''}} >50</option>
                                                                        <option value="100" {{$routes->perpage() == 100 ? 'selected' : ''}} >100</option>
                                                                    </select> 
                                                                    registros
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6" align="right">
                                                                <div id="tblList_filter" class="dataTables_filter" style="display: inline-flex;">
                                                                    <label style="display: inline-flex;">Fecha:  
                                                                        <input style="position: relative;top: -10px;margin-left: 5px; ;" type="date" id="datetable" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="">
                                                                        <button class="btn btn-primary" style="position: relative;top: -10px; margin-right: 5px;" onclick="$('#datetable').val('').trigger('change');">X</button>
                                                                    </label>
                                                                    <label style="display: inline-flex;">Buscar:  
                                                                        <input style="position: relative;top: -10px;margin-left: 5px;" type="search" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="{{$term ?? ''}}">
                                                                    </label>
                                                                </div>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                    <div id="list" class="relative" style="min-height: 380px;">
                                                        @include('scheduling.sub.list')
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


@include('scheduling.sub.mdl_init_large_form')
@include('scheduling.sub.mdl_form')
@include('scheduling.sub.mdl_large_form')
@include('scheduling.sub.mdl_delete')


@endsection
