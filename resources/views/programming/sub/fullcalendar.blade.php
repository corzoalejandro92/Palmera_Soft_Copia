@section('viewjs', 'programming/all')
<h6><i class="step-icon ft-airplay"></i>Automatización</h6>
<fieldset class="fieldspace">



    <!-- Inicio de la paleta de colores -->
    <section id="social-button">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <div class="form-group">
                            <!-- Social Buttons with Social Name -->
                            <!-- <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-facebook"><i
                                    class="custom-insect iconMenu"></i> Desinsectación</a> -->
                            <!-- <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-twitter"><i
                                    class="custom-mouse iconMenu"></i> Desratización</a> -->
                            <!-- <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-vimeo"><i
                                    class="custom-infection iconMenu"></i>Desinfección</a>                         -->
                            <!-- <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-tumblr"><i
                                    class="custom-wash iconMenu"></i>Lavado de tanques</a> -->
                            <!-- <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-yahoo"><i
                                    class="custom-fix iconMenu"></i>Arreglos locativos</a> -->
                            <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-pinterest"><span
                                    class="la la-automobile font-medium-3"></span> Traslado</a>
                            <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-soundcloud"><span
                                    class="la la-clock-o"></span> Horas Extras</a>
                            <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-github"><i
                                    class="la la-briefcase font-medium-3"></i> Reunión</a>
                            <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-success"><span
                                    class="la la-building"></span> Recoger Suministros</a>
                            <a href="#" class="btn btn-social btn-min-width mr-1 mb-1 btn-vimeo"><span
                                    class="la la-bars"></span>Servicios</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FIn de la paleta de colores -->

    <!-- Inicio de los Filtos -->

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

    <!-- inicio de horas -->

    <section id="grid-options" class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Total Horas</h4>
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

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Tecnico</th>
                                                <th>Horas Ordinarias</th>
                                                <th>Horas Extras</th>
                                                <th>Total Horas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">Alexander Martinez</th>
                                                <td>9</td>
                                                <td>3</td>
                                                <td>12</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Jhon Doe</th>
                                                <td>7</td>
                                                <td>0</td>
                                                <td>7</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- fin de las horas -->

    <!-- Start Button from configutacion -->
    <div align="right">
        <div class="btn-group mr-1 mb-1">
            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#editService">
                <i class="ft-plus"></i>&nbsp;Agregar Tarea
            </button>
        </div>
    </div>



    <!-- End Button from configutacion -->


    <!-- Start Button from configutacion -->
    <div align="right">
        <div class="btn-group dropleft mr-1 mb-1">
            <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton3" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Preferencia de Técnico
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                <a class="dropdown-item" data-toggle="modal" data-target="#edittechnical" href="#">Alenxander
                    Martinez</a>
                <a class="dropdown-item" href="#">Jhon Doe</a>
            </div>
        </div>
    </div>

    <!-- End Button from configutacion -->



    <!-- Inicio del calendario -->

    <div id='calendar'></div>


    <!-- Modal -->
    <div class="modal fade text-left" id="editService" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel16">Evento de ruta</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <fieldset class="fieldspace">
                        <div class="repeater-default">
                            <div data-repeater-list="car">
                                <div data-repeater-item>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4">
                                            <h5>Operador</h5>
                                            <fieldset class="form-group">
                                                <select class="form-control" name="operator">
                                                    <option value="">--Seleccionar--</option>
                                                    <option value="2">Juan Camilo Pedroza</option>
                                                    <option value="3">Jhoany Palmera</option>
                                                    <option value="4">Esteven Guitierrez</option>
                                                    <option value="5">Valentina Quintero</option>
                                                    <option value="6" selected="true" disabled="disabled">Andres López
                                                    </option>
                                                    <option value="7">Xiomara García</option>
                                                    <option value="8">Elizabeth López</option>
                                                    <option value="8">Jhon Morato</option>
                                                    <option value="10">Yessica Castañeda</option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <h5>Vehículo</h5>
                                                <select class="form-control" name="Auto">
                                                    <option value="----">--seleccionar--</option>
                                                    <option value="01" selected="true" disabled="disabled">MNO 176 -
                                                        Automóvil</option>
                                                    <option value="02">QCU 09C -Moto</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-1 col-sm-1 col-md-1 text-center mt-1">
                                            <button type="button" class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                                data-repeater-delete>
                                                <i class="ft-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <hr style="border-top: 1px dashed #a4aab6;">
                                    <div class="form-group overflow-hidden">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <a data-repeater-create class="btn btn-sm btn-round btn-fab btn-success text-white">
                                    <i class="ft-plus"></i>
                                </a>
                            </div>
                    </fieldset>
                    <hr>
                    <div class="row">
                        <div class="form-group col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4">
                            <label for="timesheetinput3">Fecha</label>
                            <div class="position-relative has-icon-left">
                                <input type="date" id="timesheetinput3" class="form-control" name="date">
                                <div class="form-control-position">
                                    <i class="ft-message-square"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4">
                            <div class="form-group">
                                <label for="timesheetinput5">Hora Inicio</label>
                                <div class="position-relative has-icon-left">
                                    <input type="time" id="timesheetinput5" class="form-control" name="starttime">
                                    <div class="form-control-position">
                                        <i class="ft-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-8 col-lg-4 col-xl-4">
                            <div class="form-group">
                                <label for="timesheetinput6">Hora Final</label>
                                <div class="position-relative has-icon-left">
                                    <input type="time" id="timesheetinput6" class="form-control" name="endtime">
                                    <div class="form-control-position">
                                        <i class="ft-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <!-- star service -->

                    <div class="match-height">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="card-header">
                                <h4 class="card-title">Actividad a Realizar</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="baseIcon-tab1" data-toggle="tab"
                                                aria-controls="tabIcon1" href="#tabIcon1"
                                                aria-expanded="true">Desratización <i data-toggle="tooltip"
                                                    data-placement="top" title="Editar" class="icon-pencil"
                                                    style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                    data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                    class="icon-close"
                                                    style="left: 17px;font-size: 15px;position: relative;"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="baseIcon-tab2" data-toggle="tab"
                                                aria-controls="tabIcon2" href="#tabIcon2"
                                                aria-expanded="false">Desinfección <i data-toggle="tooltip"
                                                    data-placement="top" title="Editar" class="icon-pencil"
                                                    style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                    data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                    class="icon-close"
                                                    style="left: 17px;font-size: 15px;position: relative;"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="baseIcon-tab3" data-toggle="tab"
                                                aria-controls="tabIcon3" href="#tabIcon6"
                                                aria-expanded="false">Desinsectación <i data-toggle="tooltip"
                                                    data-placement="top" title="Editar" class="icon-pencil"
                                                    style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                    data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                    class="icon-close"
                                                    style="left: 17px;font-size: 15px;position: relative;"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="modal" data-target="#success2"><i
                                                    class="ft-plus-circle"></i> Agregar</a>
                                        </li>
                                        <!-- Modal -->
                                        <div class="modal fade text-left" data-backdrop="static" id="success2"
                                            tabindex="-1" role="dialog" aria-labelledby="myModalLabel9"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success white">
                                                        <h4 class="modal-title white" id="myModalLabel9">Crear Servicio
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div
                                                                class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                                <h5 for="priority">Servicio</h5>
                                                                <select class="select2 form-control" name="productos">
                                                                    <option value="----">--Seleccionar--</option>
                                                                    <option value="01">Desratización</option>
                                                                    <option value="02">Desinfección</option>
                                                                    <option value="03">Desinsectación</option>
                                                                    <option value="04">Inmunización</option>
                                                                    <option value="05">Encarpado</option>
                                                                    <option value="06">Arreglos locativos</option>
                                                                    <option value="07">Lavado de tanques</option>
                                                                    <option value="08">Otros</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn grey btn-secondary"
                                                                data-dismiss="modal">Cancelar</button>
                                                            <button type="button" data-dismiss="modal"
                                                                class="btn btn-success"
                                                                onclick='showServiceControls(1);'>Guardar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </ul>
                                    <hr>
                                    <div class="tab-content px-1 pt-3">
                                        <!-- tag #1 -->
                                        <div role="tabpanel" class="tab-pane active" id="tabIcon1" aria-expanded="true"
                                            aria-labelledby="baseIcon-tab1">
                                            <div>
                                                <h5 class="col-md-12 mb-1"><b>Observaciones:</b> </h5>
                                                <!-- star fieldset -->
                                                <fieldset class="fieldspace">
                                                    <div class="repeater-default">
                                                        <div data-repeater-list="car">
                                                            <div data-repeater-item>
                                                                <div class="row">

                                                                    <div
                                                                        class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <div>
                                                                            <div>
                                                                                <h5>Subir archivo</h5>
                                                                            </div>
                                                                            <div>
                                                                                <div>
                                                                                    <fieldset class="form-group"
                                                                                        name="file">
                                                                                        <div class="custom-file">
                                                                                            <input type="file"
                                                                                                class="custom-file-input"
                                                                                                id="inputGroupFile01">
                                                                                            <label
                                                                                                class="custom-file-label"
                                                                                                for="inputGroupFile01">Seleccionar
                                                                                                archivo
                                                                                            </label>
                                                                                        </div>
                                                                                    </fieldset>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                                                        <h5 for="area">Observación dirigida a:</h5>

                                                                        <select class="form-control" name="observation">
                                                                            <option value="01">Administrador</option>
                                                                            <option value="02">Comerciales</option>
                                                                            <option value="03">Programación</option>
                                                                            <option value="04">Compras</option>
                                                                            <option value="05">Jefe de compras</option>
                                                                            <option value="06">Jefe de programacion
                                                                            </option>
                                                                            <option value="07">Jefe comercial</option>
                                                                            <option value="08">Coordinador MIP</option>
                                                                            <option value="09">Técnico</option>
                                                                            <option value="10">Cliente</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-10 col-sm-10 col-md-10 col-lg-10 col-lg-10">
                                                                        <h5 for="name">Observaciones del servicio</h5>
                                                                        <textarea class="form-control"
                                                                            name="observation"
                                                                            placeholder="Observaciones del servicio">
                                                                        </textarea>
                                                                    </div>


                                                                    <div
                                                                        class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                                                            data-repeater-delete>
                                                                            <i class="ft-x"></i>
                                                                        </button>
                                                                    </div>


                                                                </div>
                                                                <hr style="border-top: 1px dashed #a4aab6;">
                                                                <div class="form-group overflow-hidden">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <a data-repeater-create
                                                                class="btn btn-sm btn-round btn-fab btn-success text-white">
                                                                <i class="ft-plus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <!-- End fieldset -->

                                            </div>

                                            <hr>
                                            <!-- star materials -->

                                            <div clas="row">
                                                <h4 class="col-md-12 mb-1">Producto aplicado o Mecanismo instalado</h4>
                                                <!-- star fieldset -->
                                                <fieldset class="fieldspace">
                                                    <div class="repeater-default">
                                                        <div data-repeater-list="car">
                                                            <div data-repeater-item>
                                                                <div class="row">
                                                                    <div
                                                                        class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                                                        <h5>Sustancia/Mecanismo</h5>
                                                                        <select class="select2 form-control"
                                                                            name="category">
                                                                            <optgroup label="Sustencia">
                                                                                <option value="--" selected>
                                                                                    -seleccionar-</option>
                                                                                <option value="01">CIPERMETRINA</option>
                                                                                <option value="02">AGUA</option>
                                                                                <option value="03">FIPRONIL</option>
                                                                            </optgroup>
                                                                            <optgroup label="Mecanismo">
                                                                                <option value="01">TRAMPAS DE
                                                                                    CAPTURA</option>
                                                                                <option value="02">LAMINA ADHESIVA
                                                                                </option>
                                                                                <option value="02">LAMINA CON
                                                                                    FEROMONA</option>
                                                                            </optgroup>
                                                                        </select>
                                                                    </div>

                                                                    <div
                                                                        class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                        <label>Dosis</label>
                                                                        <input type="text" class="form-control">
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                        <label>Cantidad</label>
                                                                        <input type="text" class="form-control">
                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                                                                        <h5 for="area">Aplicacion:</h5>

                                                                        <select class="form-control" name="Aplication">
                                                                            <option value="01">Asperción</option>
                                                                            <option value="02">Gel</option>
                                                                            <option value="03">Insuflador</option>
                                                                            <option value="04">Nebulización</option>
                                                                            <option value="05">Inyección</option>
                                                                            <option value="06">Trampas de captura
                                                                            </option>
                                                                            <option value="07">Cebos</option>
                                                                            <option value="08">Brochado</option>
                                                                            <option value="09">Lamparas</option>
                                                                            <option value="10">Espolvoreo</option>
                                                                            <option value="10">Gas</option>
                                                                            <option value="10">Otro</option>
                                                                        </select>
                                                                    </div>

                                                                    <div
                                                                        class="form-group col-10 col-sm-10 col-md-10 col-lg-10 col-lg-10">
                                                                        <h5 for="name">Observaciones</h5>
                                                                        <textarea class="form-control"
                                                                            name="observation"
                                                                            placeholder="Observaciones del insumo"></textarea>
                                                                    </div>


                                                                    <div
                                                                        class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                                                            data-repeater-delete>
                                                                            <i class="ft-x"></i>
                                                                        </button>
                                                                    </div>


                                                                </div>
                                                                <hr style="border-top: 1px dashed #a4aab6;">
                                                                <div class="form-group overflow-hidden">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <a data-repeater-create
                                                                class="btn btn-sm btn-round btn-fab btn-success text-white">
                                                                <i class="ft-plus"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <!-- End fieldset -->
                                            </div>

                                            <!-- End materials -->
                                        </div>
                                        <!-- tag #2 -->
                                        <div class="tab-pane" id="tabIcon2" aria-labelledby="baseIcon-tab2">
                                            <div class="row">
                                                <div>
                                                    <h5 class="col-md-12 mb-1"><b>Observaciones:</b> </h5>
                                                    <!-- star fieldset -->
                                                    <fieldset class="fieldspace">
                                                        <div class="repeater-default">
                                                            <div data-repeater-list="car">
                                                                <div data-repeater-item>
                                                                    <div class="row">

                                                                        <div
                                                                            class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                            <div>
                                                                                <div>
                                                                                    <h5>Subir archivo</h5>
                                                                                </div>
                                                                                <div>
                                                                                    <div>
                                                                                        <fieldset class="form-group"
                                                                                            name="file">
                                                                                            <div class="custom-file">
                                                                                                <input type="file"
                                                                                                    class="custom-file-input"
                                                                                                    id="inputGroupFile01">
                                                                                                <label
                                                                                                    class="custom-file-label"
                                                                                                    for="inputGroupFile01">Seleccionar
                                                                                                    archivo</label>
                                                                                            </div>
                                                                                        </fieldset>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div
                                                                            class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                                                            <h5 for="area">Observación dirigida a:</h5>

                                                                            <select class="form-control"
                                                                                name="observation">
                                                                                <option value="01">Administrador
                                                                                </option>
                                                                                <option value="02">Comerciales</option>
                                                                                <option value="03">Programación</option>
                                                                                <option value="04">Compras</option>
                                                                                <option value="05">Jefe de compras
                                                                                </option>
                                                                                <option value="06">Jefe de programacion
                                                                                </option>
                                                                                <option value="07">Jefe comercial
                                                                                </option>
                                                                                <option value="08">Coordinador MIP
                                                                                </option>
                                                                                <option value="09">Técnico</option>
                                                                                <option value="10">Cliente</option>
                                                                            </select>
                                                                        </div>
                                                                        <div
                                                                            class="form-group col-10 col-sm-10 col-md-10 col-lg-10 col-lg-10">
                                                                            <h5 for="name">Observaciones del servicio
                                                                            </h5>
                                                                            <textarea class="form-control"
                                                                                name="observation"
                                                                                placeholder="Observaciones del servicio"></textarea>
                                                                        </div>


                                                                        <div
                                                                            class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                                                                data-repeater-delete>
                                                                                <i class="ft-x"></i>
                                                                            </button>
                                                                        </div>


                                                                    </div>
                                                                    <hr style="border-top: 1px dashed #a4aab6;">
                                                                    <div class="form-group overflow-hidden">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <a data-repeater-create
                                                                    class="btn btn-sm btn-round btn-fab btn-success text-white">
                                                                    <i class="ft-plus"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <!-- End fieldset -->

                                                </div>
                                                <hr>
                                                <!-- star materials -->

                                                <div clas="row">
                                                    <h4 class="col-md-12 mb-1">Producto aplicado o Mecanismo instalado
                                                    </h4>
                                                    <!-- star fieldset -->
                                                    <fieldset class="fieldspace">
                                                        <div class="repeater-default">
                                                            <div data-repeater-list="car">
                                                                <div data-repeater-item>
                                                                    <div class="row">
                                                                        <div
                                                                            class="form-group col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                                                            <h5>Sustancia/Mecanismo</h5>
                                                                            <select class="form-control"
                                                                                name="category">
                                                                                <optgroup label="Sustencia">
                                                                                    <option value="--" selected>
                                                                                        -seleccionar-</option>
                                                                                    <option value="01">CIPERMETRINA
                                                                                    </option>
                                                                                    <option value="02">AGUA</option>
                                                                                    <option value="03">FIPRONIL</option>
                                                                                </optgroup>
                                                                                <optgroup label="Mecanismo">
                                                                                    <option value="01">TRAMPAS DE
                                                                                        CAPTURA</option>
                                                                                    <option value="02">LAMINA ADHESIVA
                                                                                    </option>
                                                                                    <option value="02">LAMINA CON
                                                                                        FEROMONA</option>
                                                                                </optgroup>
                                                                            </select>
                                                                        </div>

                                                                        <div
                                                                            class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                            <label>Dosis</label>
                                                                            <input type="text" class="form-control">
                                                                        </div>
                                                                        <div
                                                                            class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                            <label>Cantidad</label>
                                                                            <input type="text" class="form-control">
                                                                        </div>
                                                                        <div
                                                                            class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                                                                            <h5 for="area">Aplicacion:</h5>

                                                                            <select class="form-control"
                                                                                name="Aplication">
                                                                                <option value="01">Asperción</option>
                                                                                <option value="02">Gel</option>
                                                                                <option value="03">Insuflador</option>
                                                                                <option value="04">Nebulización</option>
                                                                                <option value="05">Inyección</option>
                                                                                <option value="06">Trampas de captura
                                                                                </option>
                                                                                <option value="07">Cebos</option>
                                                                                <option value="08">Brochado</option>
                                                                                <option value="09">Lamparas</option>
                                                                                <option value="10">Espolvoreo</option>
                                                                                <option value="10">Gas</option>
                                                                                <option value="10">Otro</option>
                                                                            </select>
                                                                        </div>

                                                                        <div
                                                                            class="form-group col-10 col-sm-10 col-md-10 col-lg-10 col-lg-10">
                                                                            <h5 for="name">Observaciones</h5>
                                                                            <textarea class="form-control"
                                                                                name="observation"
                                                                                placeholder="Observaciones del insumo"></textarea>
                                                                        </div>


                                                                        <div
                                                                            class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                                                                data-repeater-delete>
                                                                                <i class="ft-x"></i>
                                                                            </button>
                                                                        </div>


                                                                    </div>
                                                                    <hr style="border-top: 1px dashed #a4aab6;">
                                                                    <div class="form-group overflow-hidden">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <a data-repeater-create
                                                                    class="btn btn-sm btn-round btn-fab btn-success text-white">
                                                                    <i class="ft-plus"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <!-- End fieldset -->
                                                </div>

                                                <!-- End materials -->
                                            </div>
                                            <!-- tag #3 -->
                                            <div class="tab-pane" id="tabIcon3" aria-labelledby="baseIcon-tab3">

                                                <div class="display-hidden serviceControls-1">
                                                    <h5 class="col-md-12 mb-1"><b>Observaciones:</b> </h5>
                                                    <!-- star fieldset -->
                                                    <fieldset class="fieldspace">
                                                        <div class="repeater-default">
                                                            <div data-repeater-list="car">
                                                                <div data-repeater-item>
                                                                    <div class="row">

                                                                        <div
                                                                            class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                            <div>
                                                                                <div>
                                                                                    <h5>Subir archivo</h5>
                                                                                </div>
                                                                                <div>
                                                                                    <div>
                                                                                        <fieldset class="form-group"
                                                                                            name="file">
                                                                                            <div class="custom-file">
                                                                                                <input type="file"
                                                                                                    class="custom-file-input"
                                                                                                    id="inputGroupFile01">
                                                                                                <label
                                                                                                    class="custom-file-label"
                                                                                                    for="inputGroupFile01">Seleccionar
                                                                                                    archivo</label>
                                                                                            </div>
                                                                                        </fieldset>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div
                                                                            class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                                                            <h5 for="area">Observación dirigida a:</h5>

                                                                            <select class="form-control"
                                                                                name="observation">
                                                                                <option value="01">Administrador
                                                                                </option>
                                                                                <option value="02">Comerciales</option>
                                                                                <option value="03">Programación</option>
                                                                                <option value="04">Compras</option>
                                                                                <option value="05">Jefe de compras
                                                                                </option>
                                                                                <option value="06">Jefe de programacion
                                                                                </option>
                                                                                <option value="07">Jefe comercial
                                                                                </option>
                                                                                <option value="08">Coordinador MIP
                                                                                </option>
                                                                                <option value="09">Técnico</option>
                                                                                <option value="10">Cliente</option>
                                                                            </select>
                                                                        </div>
                                                                        <div
                                                                            class="form-group col-10 col-sm-10 col-md-10 col-lg-10 col-lg-10">
                                                                            <h5 for="name">Observaciones del servicio
                                                                            </h5>
                                                                            <textarea class="form-control"
                                                                                name="observation"
                                                                                placeholder="Observaciones del servicio"></textarea>
                                                                        </div>


                                                                        <div
                                                                            class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                                                                data-repeater-delete>
                                                                                <i class="ft-x"></i>
                                                                            </button>
                                                                        </div>


                                                                    </div>
                                                                    <hr style="border-top: 1px dashed #a4aab6;">
                                                                    <div class="form-group overflow-hidden">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <a data-repeater-create
                                                                    class="btn btn-sm btn-round btn-fab btn-success text-white">
                                                                    <i class="ft-plus"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                    <!-- End fieldset -->

                                                </div>
                                                <!-- star materials -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- End service -->

                        <hr>
                        <h5>Otras Acciones</h5>
                        <div class="form-group">
                            <fieldset>
                                <div class="col-12 col-sm-12 col-md-8 col-lg-12 col-xl-12">
                                    <div class="case-sensitive form-control" data-tags-input-name="case-sensitive">
                                        Entregar Factura, Solicitar Planos, Revisar Mecanismos instalados
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <hr>
                        <fieldset class="fieldspace">
                            <div class="repeater-default">
                                <div data-repeater-list="car">
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="form-group col-10 col-sm-10 col-md-6 col-lg-6 col-lg-6">
                                                <h5 for="name">Observaciones</h5>
                                                <textarea class="form-control" name="observation"
                                                    placeholder="Observaciones final de la ruta"></textarea>
                                            </div>
                                            <div class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                                <h5 for="area">Observación dirigida a:</h5>

                                                <select class="form-control" name="observation">
                                                    <option value="01">Administrador</option>
                                                    <option value="02">Comerciales</option>
                                                    <option value="03">Programación</option>
                                                    <option value="04">Compras</option>
                                                    <option value="05">Jefe de compras</option>
                                                    <option value="06">Jefe de programacion</option>
                                                    <option value="07">Jefe comercial</option>
                                                    <option value="08">Coordinador MIP</option>
                                                    <option value="09">Técnico</option>
                                                    <option value="10">Cliente</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                                                <button type="button"
                                                    class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                                    data-repeater-delete>
                                                    <i class="ft-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <hr style="border-top: 1px dashed #a4aab6;">
                                        <div class="form-group overflow-hidden">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <a data-repeater-create class="btn btn-sm btn-round btn-fab btn-success text-white">
                                        <i class="ft-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </fieldset>
                        <div class="modal-footer">
                            <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



</fieldset>

<!-- Modal Preferencia de clinte-->
<div class="modal fade text-left" id="edittechnical" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel16">Alexander Martinez</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <fieldset class="fieldspace">
                    <div class="repeater-default">
                        <div data-repeater-list="car">
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-8 col-lg-3 col-xl-3">
                                        <h5>Operador</h5>
                                        <fieldset class="form-group">
                                            <select class="form-control" name="operator">
                                                <option value="">--Seleccionar--</option>
                                                <option value="2">Juan Camilo Pedroza</option>
                                                <option value="3">Jhoany Palmera</option>
                                                <option value="4">Esteven Guitierrez</option>
                                                <option value="5">Valentina Quintero</option>
                                                <option value="6" selected="true" disabled="disabled">Andres
                                                    López</option>
                                                <option value="7">Xiomara García</option>
                                                <option value="8">Elizabeth López</option>
                                                <option value="8">Jhon Morato</option>
                                                <option value="10">Yessica Castañeda</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-8 col-lg-3 col-xl-3">
                                        <h5>Cliente</h5>
                                        <fieldset class="form-group">
                                            <select class="select2 form-control" multiple>
                                                <option value="1">--Seleccionar--</option>
                                                <option value="2">Solla</option>
                                                <option value="3">Zenu</option>
                                                <option value="4">Exito</option>
                                                <option value="5">Noel</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">
                                        <div class="form-group">
                                            <h5>Servicios</h5>
                                            <select class="select2 form-control" multiple>
                                                <option value="----">--seleccionar--</option>
                                                <option value="01">Desratización</option>
                                                <option value="02">Desinfección</option>
                                                <option value="03">Desinsectación</option>
                                                <option value="04">Inmunización</option>
                                                <option value="05">Encarpado</option>
                                                <option value="05">Arreglos locativos</option>
                                                <option value="05">Lavado de tanques</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                                        <button type="button" class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                            data-repeater-delete>
                                            <i class="ft-x"></i>
                                        </button>
                                    </div>


                                </div>
                                <hr style="border-top: 1px dashed #a4aab6;">
                                <div class="form-group overflow-hidden">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <a data-repeater-create class="btn btn-sm btn-round btn-fab btn-success text-white">
                                <i class="ft-plus"></i>
                            </a>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Guardar</button>
            </div>
        </div>
    </div>c
</div>