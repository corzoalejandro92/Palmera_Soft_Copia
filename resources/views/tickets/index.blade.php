@extends('layouts.main') 

@section('title', 'Listado de Tickets')
@section('viewjs','ticket/ticket')
@section('viewcss', 'ticket/show')
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
<style>

.custom-control-label::before {
    left: -1.2rem !important;
}
.custom-control-inline{
    margin-right:0.2rem!important;
}
.custom-control {
    padding-left:2.5rem!important;
}
</style>

@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Listado de Tickets</h4>
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
                                                                        <option value="10" {{$tickets->perpage() == 10 ? 'selected' : ''}} >10 registros</option>
                                                                        <option value="25" {{$tickets->perpage() == 25 ? 'selected' : ''}} >25 registros</option>
                                                                        <option value="50" {{$tickets->perpage() == 50 ? 'selected' : ''}} >50 registros</option>
                                                                        <option value="100" {{$tickets->perpage() == 100 ? 'selected' : ''}} >100 registros</option>
                                                                    </select> 
                                                                    
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-12 col-md-2">
                                                            <div class="dataTables_length" id="tblList_priority">
                                                                <h5>Categorías</h5>
                                                                    <select style="position: relative;top: -10px;" name="tblList_category" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="" selected >Categoria</option>
                                                                        @foreach ($categories as $categorie)
                                                                            @php
                                                                                $check=$categorie->slug == $category ? 'selected' : '';
                                                                            @endphp
                                                                            <option value="{{$categorie->slug}}" {{$check}}>{{$categorie->name}}</option>
                                                                        @endforeach
                                                                    </select> 
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-2">
                                                            <div class="dataTables_length" id="tblList_priority">
                                                                <h5>Prioridad</h5>
                                                                    <select style="position: relative;top: -10px;" name="tblList_priority" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="" selected >Prioridad</option>
                                                                        @foreach ($priorities as $prioritie)
                                                                            <option value="{{$prioritie->slug}}" >{{$prioritie->name}}</option>
                                                                        @endforeach
                                                                    </select> 
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-2">
                                                            <h5>Fecha</h5>
                                                            <fieldset class="form-group position-relative" style="display: flex;">
                                                                <input style="position: relative;top: -10px;" type="date"  class="form-control" id="date" name="date" placeholder="Ingrese una fecha" value=""><button id='datebtn' style="position: relative;top: -10px;" onclick="javascript:date.value=''">X</button>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="form-group">
                                                                    <h5 style="margin:0px">Cliente</h5>
                                                                    <select class="select2 form-control" id="customer" name="crm_id" data-change="hiden-error">
                                                                        <option value="">--seleccionar--</option>
                                                                    </select>
                                                            </div>
                                                           </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-3">
                                                            <h5>Leido</h5>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="read2" checked name="read" value="" class="custom-control-input">
                                                                <label class="custom-control-label" for="read2">Cualquiera</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="read1" name="read" value="1" class="custom-control-input">
                                                                <label class="custom-control-label" for="read1">Si</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="read0" name="read" value="0" class="custom-control-input">
                                                                <label class="custom-control-label" for="read0">No</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-3">
                                                            <h5>Solucionado</h5>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="solved2" checked name="solved" value="" class="custom-control-input">
                                                                <label class="custom-control-label" for="solved2">Cualquiera</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="solved1" name="solved" value="1" class="custom-control-input">
                                                                <label class="custom-control-label" for="solved1">Si</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input type="radio" id="solved0" name="solved" value="0" class="custom-control-input">
                                                                <label class="custom-control-label" for="solved0">No</label>
                                                            </div>
                                                        
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <h5 style="margin:0">Usuario</h5>
                                                            <select class="form-control select2" data-change="hiden-error" name="users[]" id="users" data-placeholder="Técnico" autocomplete="no" multiple>
                                                                <option value="">--seleccionar--</option>
                                                            </select>
                                                        </div> 
                                                        <div class="col-sm-12 col-md-2">
                                                            <h5 style="margin:0">Orden</h5>
                                                            <select class="form-control select2" data-change="hiden-error" name="order" data-placeholder="Orden" autocomplete="no" >
                                                                <option value="created_at desc" selected>Mas Reciente</option>
                                                                <option value="created_at asc" >Menos Reciente</option>
                                                                <option value="updated_at desc">Actualizados recientemente</option>
                                                                <option value="updated_at asc">Menos actualizados</option>
                                                                <option value="count_answers desc">Mas respondidos</option>
                                                                <option value="count_answers asc">Menos respondidos</option>
                                                                
                                                            </select>
                                                        </div> 
                                                    </div>
                                                        {{-- <div class="col-md-12">
                                                            <h5 style="display: inline-flex;">Buscar</h5>
                                                                <input  name="search" class="form-control" placeholder="Titulo" aria-controls="tblList" value="{{$term ?? ''}}">
                                                            
                                                        </div> --}}
                                                    </div>
                                                    <div id="list"  class="relative" style="min-height: 380px;">
                                                        @include('tickets.sub.list')
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


@include('tickets.sub.mdl_formShow')


@endsection
