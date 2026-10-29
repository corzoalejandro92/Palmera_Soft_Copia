
@extends('layouts.main')

@section('title', 'Programación')
@section('fullcalendar','true')
@section('select2','true')
@section('tagsinput','true')



@section('content')

<!-- filtros de calendario -->
<!-- end filtros de calendario -->

    <section id="grid-options" class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filtros</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Tipo de evento</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" id="LargeSelect1">
                                            <option value="">Seleccione una opción</option>
                                            <option selected="1">Todos</option>
                                            <option value="2">Servicios</option>
                                            <option value="3">Visitas</option>
                                            <option value="4">Recordatorios</option>
                                            <option value="5">Reunión</option>
                                            <option value="6">Personal</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <h5>Cruces disponibles</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" id="LargeSelect" name="crossing">
                                            <option value="">Seleccione una opción</option>
                                            <option value="1">Usuarios</option>
                                            <option value="2">Clientes</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4 select-user display-hidden">
                                    <h5>Usuarios</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" id="LargeSelect">
                                            <option selected="">Seleccione una opción</option>
                                            <option selected="1">Todos</option>
                                            <option value="2">Juan Camilo Pedroza</option>
                                            <option value="3">Jhoany Palmera</option>
                                            <option value="4">Esteven Guitierrez</option>
                                            <option value="5">Valentina Quintero</option>
                                            <option value="6">Andres López</option>
                                            <option value="7">Xiomara García</option>
                                            <option value="8">Elizabeth López</option>
                                            <option value="8">Jhon Morato</option>
                                            <option value="10">Yessica Castañeda</option>
                                            
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4 select-customer display-hidden">
                                    <h5>Clientes</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" id="LargeSelect">
                                            <option selected="">Seleccione una opción</option>
                                            <option selected="1">Todos</option>
                                            <option value="2">Solla</option>
                                            <option value="3">Noel</option>
                                            <option value="4">Zenú</option>
                                            <option value="5">Software Web S.A.S.</option>
                                            <option value="6">Grupo Éxito</option> 
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

<!--    Contenderor de calendario-->
    @include('programming.sub.calendar')
<!--    Fin contenedor de calendario -->    
@endsection