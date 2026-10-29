@extends('layouts.main')
@section('title', 'Lista de Productos')
@section('viewcss','product/form')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')
@section('dynamic-form', 'true')
@section('fileInput', 'true')
@section('viewjs', 'product/create')
@section('colorpicker','true')



@section('content')


<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Productos</h4>
                </div>
                <div class="col-md-6" align="right">
                    @can('products.create')
                        <a class="btn btn-outline-red" href="{{route('convection.index')}}">
                            <i class="ft-plus"></i>
                            Convecciones
                        </a>
                        <button class="btn btn-outline-teal" data-click="openForm" data-id>
                            <i class="ft-plus"></i>
                            Crear Productos
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
@include('product.sub.mdl_form');
@include('product.sub.mdl_delete');
@include('product.sub.mdl_deleteDocument');
@include('product.sub.mdl_deleteMethod');
@endsection