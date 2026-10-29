@extends('layouts.main')
@section('title', 'Configuración de facturación')
@section('select2','true')
@section('icheck','true')
@section('dynamic-form', 'true')
@section('viewjs', 'billing/billing')






@section('content')


    <section id="icon-tabs">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ciclo de facturación por defecto</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('billing.sub.billingDefect')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ciclo de facturación de clientes especificos</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                           @include('billing.sub.customerSpecific')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div align="right">
            <div class="col-md-3 col-lg-3">
                    <a href="#" class="btn btn-success" name="saveForm">Guardar</a>
            </div> 
        </div>
        
        
    </section>

@endsection