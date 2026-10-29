
@extends('layouts.main')

@section('title', 'Programador')



@section('sweetAlert','true')
@section('icheck', 'true')
@section('select2','true')
@section('fullcalendar','true')
@section('fullcalendar4','true')

@section('jqueryUI','true')
@section('contextmenu','true')
@section('datepicker', 'true')
@section('timepicker','true')
@section('map','true')


@section('viewjs2', 'scheduling/common')
@section('viewjs', 'scheduling/programmer')
@section('viewcss','scheduling/programmer')



@section('content')

<!-- filtros de calendario -->
<!-- end filtros de calendario -->


<section id="grid-options" class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <form id="sendProgrammerFilter">
                                    <input type="hidden" name="crm_id_global" value="">
                                    @method('POST')
                                    @csrf
                                </form>
                                <div id="programmer" ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!--- Incluir modales del módulo -->
@include('scheduling.sub.mdl_form')
@include('scheduling.sub.mdl_large_form')
@include('scheduling.sub.mdl_delete')
<!--    Fin contenedor de calendario -->    
 
@endsection