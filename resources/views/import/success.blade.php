@extends('layouts.main')

@section('title', 'Importar Data')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('fileInput','true')
@section('select2','true')
@section('viewjs','import/index')
@section('viewcss','import/index')


@section('content')

<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Importar Data</h4>
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
                                                <div class="alert alert-success" role="alert">
                                                    <b>¡Impotación finalizada!</b> La importación a terminado de manera correcta
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
@include('infestation.sub.mdl_form')
@include('infestation.sub.mdl_delete')
@endsection