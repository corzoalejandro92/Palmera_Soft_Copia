@extends('layouts.main')
@section('title', 'Crear Inventario')
@section('fuelux', 'true')
@section('dynamic-form', 'true')
@section('viewcss', 'inventory/index')
@section('viewjs', 'inventory/technical')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')


@section('content')


<section id="configuration">

    <div class="row">
        <div class="col-12">
            <div class="card pb-2">
                <div class="card-header">
                    <h4 class="card-title">Mi inventario</h4>
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
    </div>
    <input type="hidden" name="_viewtypesform" value="view_types_form">
    <textarea id="category_tree_data" style="display: none;"></textarea>
</section>


<!--- Incluir modales del módulo -->
@include('inventory.sub.mdl_form')
@include('inventory.sub.mdl_delete')
@include('inventory.sub.mdl_setting')
@include('inventory.sub.mdl_moveInventory')



@endsection
