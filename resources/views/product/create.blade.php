@extends('layouts.main')
@section('title', 'Crear Producto')
@section('viewjs', 'product/create')









@section('content')

<section id="icon-tabs">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Producto</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        @include('product.forms.productForm', ['products'=> new App\Product])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detalle del producto</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        @include('product.forms.detailForm', ['products'=> new App\Unit])
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Especificación Detallada</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        @include('product.forms.specificationForm')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Documentacion</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        @include('product.forms.fileForm')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Metodos</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        @include('product.forms.methodForm')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div align="right">
        <div class="col-md-3 col-lg-3">
            <button type="submit" class="btn btn-success" value="enviar"><i class="la la-check-square-o"></i>
                Guardar</button>
        </div>
    </div>


</section>


@endsection