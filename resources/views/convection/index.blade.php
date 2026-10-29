@extends('layouts.main')
@section('title', 'Convenciones')





@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')
@section('dynamic-form', 'true')
@section('fileInput', 'true')
@section('colorpicker','true')

@section('viewjs', 'convection/index')
@section('viewcss', 'convection/index')



@section('content')


<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Convenciones</h4>
                </div>
                <div class="col-md-6" align="right">
                    @can('crms.create')
                        <button class="btn btn-outline-teal" data-click="openForm" data-id>
                            <i class="ft-plus"></i>
                            Crear Convección
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
                                                <div id="list" class="relative" style="min-height: 380px; width: 100%">
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

@include('convection.sub.mdl_delete')
@endsection