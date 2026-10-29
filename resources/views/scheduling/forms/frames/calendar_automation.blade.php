<!-- Inicio de la paleta de colores -->

<!-- FIn de la paleta de colores -->

<!-- Inicio de los Filtos -->

<section id="grid-options" class="row display-hidden">
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
                                <select class="select2 form-control input-lg" multiple>
                                    <option value="">--Seleccionar--</option>
                                    <option value="1">Todos</option>
                                    <option value="2">Servicios</option>
                                    <option value="3">Visitas</option>
                                    <option value="4">Recordatorios</option>
                                    <option value="5">Reunión</option>
                                    <option value="6">Personal</option>
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-md-4">
                            <h5>Operador</h5>
                            <fieldset class="form-group position-relative">
                                <select class="select2 form-control input-lg"  multiple>
                                    <option value="0">--Seleccionar--</option>
                                    <option value="1">Todos</option>
                                    <option value="2">Juan Camilo Pedroza</option>
                                    <option value="3">Esteven Guitierrez</option>
                                    <option value="4">Valentina Quintero</option>
                                    <option value="5">Andres López</option>
                                    <option value="6">Xiomara García</option>
                                    <option value="7">Elizabeth López</option>
                                    <option value="8">Jhon Morato</option>
                                    <option value="9">Yessica Castañeda</option>

                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <h5>Clientes</h5>
                            <fieldset class="form-group position-relative">
                                <select class="select2 form-control input-lg" id="LargeSelect" multiple>
                                    <option value="">--Seleccionar--</option>
                                    <option value="1">Todos</option>
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

<!-- Fin de los Filtos -->


<!-- Start Button from configutacion 
scheduling/automation/covert-white-spaces
-->
<div align="right">
    <div class="btn-group mb-1">
    <button class="btn btn-dark" style="position: relative;" type="button" data-toggle="modal" data-target="#covertSpaces"  data-click="covertSpaces" data-enabled="0" data-automation_date="1" data-id>
        <i class="ft-times"></i>&nbsp; 
        @new(['date' => '2021-10-04', 'title' => 'Agregar/Actualizar Traslados', 'text' => 'Esta opción te permite rellenar los espacios entre servicios con traslados de rutas, es ideal para cuando ya has ajustado la ruta y solo requueres poner traslados entre espacios.']) 
        Agregar/Actualizar Traslados
    </button>&nbsp;

    <button class="btn btn-danger" style="position: relative;" type="button" data-toggle="modal" data-target="#editService"  data-click="openForm" data-enabled="0" data-automation_date="1" data-id>
        <i class="ft-plus"></i>&nbsp;Agregar Evento
    </button>  
    </div>
</div>

<!-- Inicio del calendario -->

<div id='calendar'></div>


<!-- inicio de horas -->

<section id="grid-options" class="row">
    <div class="col-md-12 total">
        @include('scheduling/forms/frames/total_hours_table')
    </div>
</section>

<!-- fin de las horas -->