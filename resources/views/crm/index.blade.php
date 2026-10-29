@extends('layouts.main')
@section('title', 'Clientes')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')
@section('dynamic-form', 'true')
@section('fileInput', 'true')
@section('viewjs', 'crm/customerForm')
@section('viewcss','crm/show')
@section('map','true')

@section('content')


<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">CRM</h4>
                </div>
                <div class="col-md-6" align="right">
                    @can('crms.create')
                        <button style="margin-right: 15px;" class="btn btn-outline-danger" data-click="openFormSimple" data-id>
                            <i class="ft-plus"></i>
                            Crear Cliente Simplificado
                        </button>

                        <button class="btn btn-outline-teal" data-click="openForm" data-id>
                            <i class="ft-plus"></i>
                            Crear Cliente
                        </button>
                    @endcan

                </div>
                <div class="col-md-12">&nbsp;</div>
                <div class="col-md-12" style="margin-bottom: 10px;">
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
                                                                            <option value="10" {{$crms->perpage() == 10 ? 'selected' : ''}} >10</option>
                                                                            <option value="25" {{$crms->perpage() == 25 ? 'selected' : ''}} >25</option>
                                                                            <option value="50" {{$crms->perpage() == 50 ? 'selected' : ''}} >50</option>
                                                                            <option value="100" {{$crms->perpage() == 100 ? 'selected' : ''}} >100</option>
                                                                        </select> 
                                                                        registros
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6" align="right">
                                                                    <div id="tblList_filter" class="dataTables_filter" style="display: inline-flex;">
                                                                        <label style="display: inline-flex;">Buscar:  
                                                                            <input style="position: relative;top: -10px;margin-left: 5px;" type="search" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="{{$term ?? ''}}">
                                                                        </label>
                                                                    </div>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                        <div id="list" class="relative" style="min-height: 380px;">
                                                            @include('crm.sub.list')
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
        </div>
    </section>


<!--- Incluir modales del módulo -->
@include('crm.sub.mdl_form')
@include('crm.sub.mdl_delete')
@endsection