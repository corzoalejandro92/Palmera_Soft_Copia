@extends('layouts.main')

@section('title', 'Consecutivos')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('fileInput','true')
@section('viewjs','setting/index')

{{-- @section('viewcss','user/form')
 --}}

@section('content')


<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Configuración de consecutivo</h4>
                </div>
                <div class="col-md-6" align="right">
                    <button class="btn btn-outline-teal" data-click="openForm" data-id>
                        <i class="ft-plus"></i>
                        Crear Consecutivos                     
                    </button>
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
                                                <div id="listSetting" class="relative" style="min-height: 380px;">
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
@include('setting.sub.mdl_form');
@endsection
