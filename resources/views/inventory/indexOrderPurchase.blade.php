@extends('layouts.main')
@section('title', 'Crear orden de compra')
@section('viewcss', 'inventory/index')
@section('viewjs', 'inventory/orderPurchase')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')


@section('content')


<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Orden de compra</h4>
                </div>
                <div class="col-md-6" align="right">
                    <button class="btn btn-outline-teal" data-click="openFormOrderPurchase" data-id>
                        <i class="ft-plus"></i>
                        Crear orden de compra
                    </button>
                    @can('orderPurchases.general')
                    <button class="btn btn-outline-red" data-click="openFormGeneralOrderPuchase" data-id>
                        <i class="ft-plus"></i>
                        Unificar ordenes de compra
                    </button>
                    @endcan
                </div>
            </div>
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
                                        <div id="listOrderPurchase" class="relative" style="min-height: 380px;">
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

</section>


<!--- Incluir modales del módulo -->
@include('inventory.sub.mdl_formOrderPurchase')
@include('inventory.sub.mdl_delete')




@endsection