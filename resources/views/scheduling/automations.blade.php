@extends('layouts.main') 

@section('title', 'Automatizaciones')
@section('viewcss','user/form')
@section('viewjs','scheduling/automation/index')
@section('datatables', 'true')
@section('select2','true')
@section('sweetAlert','true')
@section('fileInput','true')
@section('ckeditor', 'true')
@section('tagsinput', 'true')

@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Automatizaciones realizadas</h4>
                    </div>
                    <div class="col-md-6" align="right">
                        @can('scheduling.automation')
                        <a class="btn btn-warning bg-warning bg-darken-2" href="{{route('scheduling.automationManual')}}">
                            <i class="ft-plus"></i>
                            Rutas A Mano
                        </a>
                    @endcan
                        @can('scheduling.automation')
                            <a class="btn btn-teal bg-teal bg-darken-4" href="{{route('scheduling.automationCreate')}}">
                                <i class="ft-plus"></i>
                                Crear Automatización
                            </a>
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
@include('scheduling.sub.mdl_large_form')
@include('scheduling.sub.mdl_delete')

@endsection
