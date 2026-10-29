@extends('layouts.main')
@section('title', 'Crear Inventario')
@section('fuelux', 'true')
@section('dynamic-form', 'true')
@section('viewcss', 'inventory/index')
@section('viewjs', 'inventory/index')
@section('viewjs2', 'inventory/create')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')


@section('content')


<section id="configuration">
    <div class="row">
        @if(\App\Integration::find(1)->enabled && 
            in_array(
                \App\Headquarter::find(
                    session('headquarter_id')
                )->id , \App\Integration::find(1)->headquarters
            ))
            <div class="col-md-12">
            <div class="alert alert-success" style="display: flex;"> 
                <b>Itegración Foxcon activa para {{ session('headquarter_name') }}</b> 
                @if(Auth::user()->roles->pluck('slug')->contains('root') || Auth::user()->roles->pluck('slug')->contains('boss_purchasing') || Auth::user()->roles->pluck('slug')->contains('purchasing'))
                    <li>
                        <a type="button" target="_blank" class="btn btn-success btn-sm ml-1" href="{{ route('inventory.foxcon')}}"> &nbsp;
                            Ver movimientos Foxcon
                        </a>
                        <a type="button"  class="btn btn-info btn-sm ml-1" href="#" 
                        data-click="LisInventory"
                        data-force="true"
                        > &nbsp;
                            Ver inventario
                        </a>
                        <a type="button"  class="btn btn-warning btn-sm ml-1" href="#" 
                        data-click="LisTransferInventory"
                        data-force="true"
                        > &nbsp;
                            Ver transferencias
                        </a>
                        <a type="button"  class="btn btn-danger btn-sm ml-1" href="#" 
                        data-click="inyectInventoryForm"
                        data-force="true"
                        > &nbsp;
                            Entradas de mercancías
                        </a>
                    
                    </li>
                @endif
                
            </div>
            </div>
    @else
    <div class="col-md-12">
        <div class="alert alert-danger"> 
            <b>La integracion Foxcon no esta activa para esta sede de palmera</b>
        </div>
        </div>
    @endif
        <div class="col-4 compress-padding">
            <div class="card pb-2">
                <div class="card-header">
                    <h4 class="card-title">Navegación de Bodegas</h4>
                </div>
                <div class="card-content collapse show">
                    
                    <div class="card-body card-dashboard">

                        <div class="row">
                            <div class="col-12">
                                <input type="text" class="form-control" id="search" placeholder="Filtrar por nombre">
                                <small>Presiona enter para buscar</small>
                            </div>
                        </div>

                        <br><br>
                        
                        @include('inventory.forms.tree')
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8 compress-padding">
            <div class="card pb-2">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <section id="configuration">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card pb-2">
                                            <div class="card-content collapse show">
                                                <div class="card-body card-dashboard">
                                                    <div id="list" class="relative" style="min-height: 77vh;">
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
@include('inventory.sub.mdl_moveInventory')
@include('inventory.sub.mdl_form')
@include('inventory.sub.mdl_setting')
@include('inventory.sub.mdl_delete')



@endsection
