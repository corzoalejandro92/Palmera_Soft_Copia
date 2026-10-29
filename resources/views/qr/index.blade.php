@extends('layouts.main') 

@section('title', 'Lista QRs')
@section('viewcss','qr/form')
@section('datatables', 'true')
@section('select2','true')
@section('sweetAlert','true')
@section('icheck', 'true')
@section('viewjs','qr/index')

@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">QRs</h4>
                    </div>
                    <div class="col-md-6" align="right">
                        @can('qrs.create')
                            <button class="btn btn-outline-warning" data-click="checkQr" data-id>
                                <i class="ft-plus"></i>
                                Comprobar Qr
                            </button>
                            <button class="btn btn-outline-teal" data-click="openForm" data-id>
                                <i class="ft-plus"></i>
                                Generar QR
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
@include('qr.sub.mdl_form')
@include('qr.sub.mdl_delete')

@endsection

