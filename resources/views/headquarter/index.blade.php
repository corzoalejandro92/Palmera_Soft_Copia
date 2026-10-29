@extends('layouts.main')

@section('title', 'Lista de Sedes')
@section('datatables', 'true')
@section('select2','true')
@section('sweetAlert','true')
@section('map','true')

@section('viewjs','headquarter/index')

@section('content')


<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Sedes</h4>
                </div>
                <div class="col-md-6" align="right">
                    @can('headquarters.create')
                        <button class="btn btn-outline-teal" data-click="openForm" data-id>
                            <i class="ft-plus"></i>
                            Crear Sede
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

@endsection

<!--- Incluir modales del módulo -->
@include('headquarter.sub.mdl_form');
@include('headquarter.sub.mdl_form_zone');
@include('inventory.sub.mdl_setting')
@include('headquarter.sub.mdl_delete');