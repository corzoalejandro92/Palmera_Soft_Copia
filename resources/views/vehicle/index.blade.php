@extends('layouts.main')
@section('title', 'Lista vehículos')
@section('datatables', 'true')
@section('dynamic-form', 'true')
@section('viewjs', 'vehicle/create')
@section('viewcss', 'vehicle/index')
@section('select2','true')
@section('sweetAlert','true')

@section('content')

<section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Vehículo</h4>
                    </div>
                    <div class="col-md-6" align="right">
                        @can('vehicles.create')
                            <button class="btn btn-outline-teal" data-click="openForm" data-id>
                                <i class="ft-plus"></i>
                                Crear Vehiculo
                            </button>
                        @endcan
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
@include('vehicle.sub.mdl_form')
@include('vehicle.sub.mdl_delete')
@include('vehicle.sub.mdl_deleteDocument')
@endsection
