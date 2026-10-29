@extends('layouts.main')
@section('title', 'Generar Proforma')
@section('icheck','true')
@section('viewjs', 'billing/create')
@section('select2','true')
@section('sweetAlert','true')
@section('datatables', 'true')



@section('content')


<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Proforma</h4>
                </div>
                <div class="col-md-6" align="right">
                    <button class="btn btn-outline-teal" data-click="openForm" data-id>
                        <i class="ft-plus"></i>
                        Crear Proforma
                    </button>
                </div>
            </div>
            
            <section class="row" id="filteContainer">
                @include('layouts.loading.full')  
            </section>

            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <section id="configuration">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card pb-2">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <div id="listProforma" class="relative" style="min-height: 380px;">
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
@include('billing.sub.mdl_form')
{{-- @include('billing.sub.mdl_delete') --}}