
@extends('layouts.main')

@section('title', 'Automatización en programación de servicios')



@section('wizard', 'true')
@section('sweetAlert','true')
@section('icheck', 'true')
@section('select2','true')
@section('fullcalendar','true')
@section('fullcalendar4','true')
@section('datepicker', 'true')
@section('timepicker','true')
@section('map','true')


@section('viewjs2', 'scheduling/common')
@section('viewjs', 'scheduling/automation/create')
@section('viewcss','scheduling/automation')



@section('content')

<!-- filtros de calendario -->
<!-- end filtros de calendario -->


<section id="grid-options" class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Proceso de automatización</h4>
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
                        <form action="#" class="steps-validation" id="automation">
                            {{ method_field( 'POST') }}  
                            {{ csrf_field() }}
                                <!-- Step 1 -->
                                @include('scheduling.forms.steps.step1')
                                <!-- Step 2 -->
                                @include('scheduling.forms.steps.step2')
                                <!-- Step 3 -->
                                @include('scheduling.forms.steps.step3')
                            <!-- Step 4 -->
                            @include('scheduling.forms.steps.step4')
                            <!-- Step 5-->
                            @include('scheduling.forms.steps.step5')
                        </form>
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