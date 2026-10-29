@extends('layouts.main')
@section('title', 'Lista de Servicios')
@section('viewcss','service/index')
@section('select2','true')
@section('icheck','true')
@section('dynamic-form', 'true')
@section('sweetAlert','true')
@section('viewjs','service/create')


@section('content')


<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Servicio</h4>
                </div>
                @can('services.create')
                <div class="col-md-6" align="right">
                    <button class="btn btn-outline-teal" data-click="openForm" data-id>
                        <i class="ft-plus"></i>
                        Crear Servicio
                    </button>
                </div>
                @endcan
            </div>

            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <section id="configuration">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card pb-2">
                                        <div class="card-content collapse show">
                                            <div class="col-12 text-md-right p-1">
                                                <div id="tblList_filter" class="dataTables_filter display-inline">
                                                    <label><input type="search" name="search" class="form-control " placeholder="Buscar..." aria-controls="tblList"></label>
                                                </div>
                                            </div>
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
@include('service.sub.mdl_form')
@include('service.sub.mdl_delete')
@endsection