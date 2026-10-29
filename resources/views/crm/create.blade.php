@extends('layouts.main')
@section('title', 'Crear cliente')
@section('select2','true')
@section('icheck','true')
@section('dynamic-form', 'true')
@section('fileInput', 'true')



@section('content')


    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cliente</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('crm.forms.customerForm')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="#" class="steps-validation">
                                 @include('crm.forms.contactForm')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sucursales</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="#" class="steps-validation">
                                @include('crm.forms.officeForm')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Asignación de Técnicos</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="#" class="steps-validation">
                                @include('crm.forms.technicalForm')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Archivos</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="#" class="steps-validation">
                                @include('crm.forms.fileForm')
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        <div align="right">
                <div class="row icheck_minimal skin checkboxCustomer col-md-3" name="quote" style="z-index:1000!important;">
                    <fieldset>
                        <input type="checkbox" id="input-5">
                        <label for="input-5">Crear Cotización</label>
                    </fieldset>
                </div>
                <div class="col-md-3 col-lg-3">
                       <a href="#" class="btn btn-success" name="saveForm" id="redirect">Guardar</a>
                </div>           
                   
        </div>
    </div>
</section>

@endsection