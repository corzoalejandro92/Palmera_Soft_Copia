@extends('layouts.main')

@section('title', 'Listado de mecanismos')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('fileInput','true')
@section('select2','true')
@section('viewjs','crm/mechanism-list')
@section('viewcss','crm/mechanism-list')


@section('content')

<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Listado de mecanismos > {{$crm->name}} > {{$office->name}} >  {{$area->name}}</h4>
                </div>
                @canAny(['infestations.create'])
                    <div class="col-md-6" align="right">
                        <button class="btn btn-dark d-flex align-items-center" data-click="printQrsSelected" data-crm-id="{{$crm->id}}" data-office-id="{{$office->id}}">
                            <i class="las la-qrcode la-2x"></i>
                            <span>Imprimir Qrs</span>
                        </button>
                    </div>
                @endcanAny
            </div>

            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <section id="mechanisms">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card pb-2">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <div id="list" class="relative" style="min-height: 380px;">
                                                    <form action="crm/mechanisms/qrs" onsubmit="return;">
                                                        <table class="datatable table table-striped table-borderless">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 100px;" >
                                                                        <div class="d-flex align-items-center" style="gap: 1rem;">
                                                                            <input type="checkbox" id="checkAll"/> <label for="checkAll" class="m-0">Todos</label>
                                                                        </div>
                                                                    </th>
                                                                    <th>Convención</th>
                                                                    <th>Mecanismo</th>
                                                                    <th>Categoría</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($list as $fileExtend)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="checkbox" class="check" id="check{{$fileExtend->id}}" value="{{$fileExtend->id}}"/>
                                                                        </td>
                                                                        <td>{{$fileExtend->extendible->slug}} #{{$fileExtend->num}}</td>
                                                                        <td>{{$fileExtend->extendible->name}}</td>
                                                                        <td>{{$fileExtend->extendible->category->name}}</td>
                                                                        <td></td>
                                                                @endforeach
                                                        </table>
                                                    </form>
                                                    
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
@endsection