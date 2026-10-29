@extends('layouts.main')
@section('title', 'Informes')
@section('dashboard', 'true')
@section('select2','true')
@section('viewjs','home/index')
@section('datatables', 'true')
@section('sweetAlert','true')

@section('content')
<div class="row">
    <div class="col-12">
        <section id="icon-tabs">
            <div class="row">
                <div class="col-12">
                    
        
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
                                                            <h3>No hay datos suficientes para generar informes</h3>
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
    </div>
</div>




@endsection