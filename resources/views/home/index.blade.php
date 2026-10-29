@extends('layouts.main')
@section('title', 'Home')
@section('dashboard', 'true')
@section('select2','true')
@section('viewjs','home/index')
@section('datatables', 'true')
@section('sweetAlert','true')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row card-title">
            <div class="col-md-6">
                <h4 class="card-title top-5" style="margin-top: 27px;">Indicadores</h4>
                <form id="dasboardMonth" action="" method="POST" class="form-row">
                    {{ method_field('POST') }} 
                    {{ csrf_field() }}
                    <div class="col-4">
                        <input type="month" name="start_date" id="start_date" class="form-control" value="{{Jenssegers\Date\Date::now()->format('Y-m')}}">
                    </div>
                    <div class="col-4">
                        <input type="submit" class="btn btn-primary" value="Filtrar">
                    </div>
                </form>
            </div>
        </div>


        {{-- <section class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filtros</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse" class="collapsed"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="card">
                                        <div id="filterContent">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}




        @can('dashboards.commercial')
            <section class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informes Comerciales</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <div id="tableCommercial">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endcan

        @can('dashboards.programming')
            <section id="grid-options" class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Programacion</h4>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div id="tableProgramming">

                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </section>
        @endcan

        {{-- <section id="grid-options" class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tecnicos</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div id="tableTechnical">

                        </div>
                    </div>
                </div>
            </div>
        </section> --}}

    </div>
</div>




@endsection