@extends('layouts.main')
@section('title', 'Crear Usuario')
@section('dynamic-form', 'true')
@section('viewcss','user/form')

@section('content')


    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('user.forms.userForm')
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

@endsection