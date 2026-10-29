@extends('layouts.main')
@section('title', 'Formato de cotizaciones')
@section('viewcss','quote/index')
@section('datatables', 'true')
@section('select2','true')
@section('tooltip','true')
@section('sweetAlert','true')
@section('icheck', 'true')
@section('ckeditor', 'true')
@section('viewjs', 'quote/config')


@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Formato de Cotizaciones</h4>
                    </div>
                    @can('quotes.create')
                        <div class="col-md-6" align="right">
                            <button  type="button" class="btn btn-outline-teal" data-formart="" data-click="openConfigForm">
                                <i class="ft-plus"></i>
                                Crear Formato
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
@include('quote.sub.mdl_form')
@include('quote.sub.mdl_email')
@include('service.sub.mdl_delete')
@include('quote.sub.mdl_supplies_form')
@include('quote.sub.mdl_delete')
@endsection

