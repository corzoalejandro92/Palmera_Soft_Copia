@extends('layouts.main')
@section('title', 'Crear Rol')
@section('select2','true')
@section('icheck','true')




@section('content')


    <section id="icon-tabs">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol.forms.rolForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Programación</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol.forms.programmingForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">CRM</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol.forms.crmForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cotizaciones</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol.forms.quoteForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Servicios</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol.forms.serviceForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Usuario</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol.forms.userForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Configuración</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol.forms.configurationForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div align="right">
            <div class="col-md-3 col-lg-3">
                    <a href="#" class="btn btn-success" name="saveForm">Guardar</a>
            </div> 
        </div>
        
        
    </section>

@endsection