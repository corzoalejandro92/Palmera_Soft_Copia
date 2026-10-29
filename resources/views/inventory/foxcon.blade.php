@extends('layouts.main')
@section('title', 'Movimientos Foxcon')
@section('fuelux', 'true')
@section('dynamic-form', 'true')
@section('viewcss', 'inventory/foxcon')
@section('viewjs', 'inventory/foxcon')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')
@section('datepicker','true')
@section('toastr','true')



@section('content')

<input name="bnacional" value="{{ $bnacional ?? null }}" type="hidden">
<section id="configuration">
    <div class="row">
        <div class="col-12 compress-padding">
            <div class="card pb-2">
                <div class="card-header">
                    <h4 class="card-title">{{ $bnacional ? 'MOVIMIENTOS DE LA BODEGA NACIONAL': 'MOVIMIENTOS DE '. strtoupper( session('headquarter_name') ) }}</h4>
                </div>
                <div class="card-content collapse show">
                    
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_length" id="tblList_length">
                                    <label style="display: inline-flex;">Mostrar 
                                        <select style="position: relative;top: -10px;margin-left: 5px;margin-right: 5px;" name="tblList_length" aria-controls="tblList" class="form-control form-control-sm">
                                            <option value="10" {{$documents->perpage() == 10 ? 'selected' : ''}} >10</option>
                                            <option value="25" {{$documents->perpage() == 25 ? 'selected' : ''}} >25</option>
                                            <option value="50" {{$documents->perpage() == 50 ? 'selected' : ''}} >50</option>
                                            <option value="100" {{$documents->perpage() == 100 ? 'selected' : ''}} >100</option>
                                        </select> 
                                        registros
                                    </label>
                                </div>
                            </div>
                            {{-- <div class="col-sm-12 col-md-6" align="right">
                                    <div id="tblList_filter" class="dataTables_filter" style="display: inline-flex;">
                                        <label style="display: inline-flex;">Fecha:  
                                            <input style="position: relative;top: -10px;margin-left: 5px; ;" type="date" id="datetable" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="">
                                            <button class="btn btn-primary" style="position: relative;top: -10px; margin-right: 5px;" onclick="$('#datetable').val('').trigger('change');">X</button>
                                        </label>
                                        <label style="display: inline-flex;">Buscar:  
                                            <input style="position: relative;top: -10px;margin-left: 5px;" type="search" class="form-control form-control-sm" placeholder="" aria-controls="tblList" value="{{$term ?? ''}}">
                                        </label>
                                    </div>
                                
                            </div> --}}
                            
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="list" class="relative" style="min-height: 77vh;">
                                    @include('layouts.loading.full')
                                    <table id="tblListFoxcon" class="table table-striped table-bordered zero-configuration" >
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width:70px;">Sinc.</th>
                                                <th class="text-center" style="width:200px;">Documento <br>
                                                    <select class="form-control" name="documento">
                                                        <option value="">Todos</option>
                                                       @foreach (\App\ConfigurarionHeadquarterFoxcon::where('warehouse_id', $heaquarterWarehouseId)->get() as  $documentType)
                                                          
                                                           <option value="{{ $documentType->id }}" {{app('request')->input('documento') ==  $documentType->id ? 'selected': '' }}> {{ $documentType->nomenclature }}</option>
                                                       @endforeach 
                                                    </select>
                                                </th>
                                                <th class="text-center" style="width:150px;">Número <br>
                                                    <input class="form-control" type="text" name="numero" placeholder="Filtrar..." value="{{app('request')->input('numero')  }}">
                                                </th>
                                                <th class="text-center" style="width:300px;">Grabación <br>
                                                    <input type="text"  class="form-control" style="text-align: center;"  name="range_date" placeholder="Fecha inicio">
                                                </th> 
                                                <th class="text-center">Movimientos <br>
                                                   <div style="display: inline-flex;">
                                                    <input class="form-control" type="text" name="bodega" placeholder="Filtrar Bodega ..." value="{{app('request')->input('bodega')  }}"> &nbsp; 
                                                    <input class="form-control" type="text" name="producto" placeholder="Filtrar Producto ..." value="{{app('request')->input('producto')  }}">
                                                   </div>
                                                </th>
                                                <th style="width:200px;">Origen</th>
                                                <th class="nofilter text-center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    
                                        
                                    
                                        </tbody>
                                    </table>
                                    <div id="list-footer">
                                        
                                    </div>
                                    
                                </div>
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
