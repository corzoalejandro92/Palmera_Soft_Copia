

@extends('layouts.main')
@section('title', 'Crear Servicio')

@section('viewjs', 'quote/create')
@section('viewcss','quote/index')

@section('wizard', 'true')
@section('icheck', 'true')
@section('datepicker', 'true')
@section('dynamic-form', 'true')
@section('select2','true')
@section('wPaint','true')
@section('lightbox2','true')


@section('content')

    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cotización</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="#" class="steps-validation">

                                <!-- Step 1 -->
                                @include('quote.forms.steps.step1')

                                <!-- Step 2 -->
                                @include('quote.forms.steps.step2')
                                
                                <!-- Step 3 -->
                                @include('quote.forms.steps.stepAdvance')

                                <!-- Step 4 -->
                                @include('quote.forms.steps.step4')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
