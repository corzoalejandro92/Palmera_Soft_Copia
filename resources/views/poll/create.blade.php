

@extends('layouts.main')
@section('title', 'Crear Encuesta')
@section('viewjs', 'poll/create')
@section('viewcss', 'poll/create')
@section('summernote', 'true')
@section('wizard', 'true')
@section('sweetAlert','true')
@section('dynamic-form', 'true')



@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Encuesta</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                                @include('poll.forms.pollForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!--- Incluir modales del módulo -->
@include('poll.sub.mdl_delete')

@endsection


