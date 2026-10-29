@section('viewjs', 'quote/create')
<h6><i class="step-icon icon-equalizer"></i>Servicio</h6>


<fieldset class="fieldspace">


    <!-- star area -->

    <div class="row match-height">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card-header">
                <h4 class="card-title">Sucursales</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item fileControls-1 display-hidden">
                            <a class="nav-link active" id="baseIcon-tab1" data-toggle="tab" aria-controls="tabIcon1"
                                href="#tabIcon1" aria-expanded="true">Bello<i data-toggle="tooltip" data-placement="top"
                                    title="Editar" class="icon-pencil"
                                    style="left: 17px;font-size: 15px;position: relative;"></i><i data-toggle="tooltip"
                                    data-placement="top" title="Eliminar" class="icon-close"
                                    style="left: 17px;font-size: 15px;position: relative;"></i>
                            </a>
                        </li>
                        <li class="nav-item fileControls-1 display-hidden">
                            <a class="nav-link" id="baseIcon-tab2" data-toggle="tab" aria-controls="tabIcon2"
                                href="#tabIcon2" aria-expanded="false">Itaguí<i data-toggle="tooltip"
                                    data-placement="top" title="Editar" class="icon-pencil"
                                    style="left: 17px;font-size: 15px;position: relative;"></i><i data-toggle="tooltip"
                                    data-placement="top" title="Eliminar" class="icon-close"
                                    style="left: 17px;font-size: 15px;position: relative;"></i>
                            </a>
                        </li>
                        <li class="nav-item fileControls-1 display-hidden">
                            <a class="nav-link" id="baseIcon-tab3" data-toggle="tab" aria-controls="tabIcon3"
                                href="#tabIcon3" aria-expanded="false">Estrella<i data-toggle="tooltip"
                                    data-placement="top" title="Editar" class="icon-pencil"
                                    style="left: 17px;font-size: 15px;position: relative;"></i><i data-toggle="tooltip"
                                    data-placement="top" title="Eliminar" class="icon-close"
                                    style="left: 17px;font-size: 15px;position: relative;"></i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="modal" data-target="#success"><i
                                    class="ft-plus-circle"></i> Agregar
                            </a>
                        </li>



                        <!-- Modal -->
                        <div class="modal fade text-left" data-backdrop="static" id="success" tabindex="-1"
                            role="dialog" aria-labelledby="myModalLabel9" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-success white">
                                        <h4 class="modal-title white" id="myModalLabel9"><i
                                                class="ft-pie-chart"></i>Nueva Sucursal</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="form-group col-12 col-sm-12 col-md-4 col-lg-10 col-xl-10">
                                                <h5>Nombre</h5>
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="Ingrese el nombre de la sucursal">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn grey btn-secondary"
                                                data-dismiss="modal">Cancelar</button>
                                            <button type="button" data-dismiss="modal" class="btn btn-success"
                                                onclick='showFileControls(1)'>Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ul>
                    <hr>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-5 col-xl-5 fileControls-1 display-hidden">
                        <div class="dropzone dropzone-area" data-toggle="modal" data-target="#drawFile">
                            <div class="dz-message">Subir Plano</div>
                        </div>
                    </div>
                    <div class="tab-content px-1 pt-3">
                        <div role="tabpanel" class="tab-pane active" id="tabIcon1" aria-expanded="true"
                            aria-labelledby="baseIcon-tab1">
                            <div class="row">


                                <!-- titulo del area -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mapControls-1 display-hidden">
                                    <h5> <b>Sucursal:</b> Bello</h5>

                                </div>

                                <!-- plano de carga con los botenes de opciones -->
                                <div
                                    class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mapControls-1 my-1 display-hidden">
                                    <h5 class="card-title">Plano</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="modal" data-target="#drawMapMecanismo" data-toggle="tooltip"
                                            data-placement="top" title="Ubicar Mecanismo" class="icon-pin"
                                            data-toggle="modal" data-target="#success"
                                            onclick="showMapControls(2);setBgImage();"></i>
                                        <i data-toggle="modal" data-target="#drawMaprutas" data-toggle="tooltip"
                                            data-placement="top" title="Ubicar Rutas" class="icon-cursor-move"
                                            onclick="showMapControls(3);setBgImage();"></i>
                                        <i data-toggle="modal" data-target="#drawMapriesgos" data-toggle="tooltip"
                                            data-placement="top" title="Señalar Riesgos" class="icon-fire"
                                            onclick="showMapControls(4);setBgImage();"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano">
                                        <a href="<?=url("images/plano_planta_palmerajunior.png") ?>"
                                            data-lightbox="images" data-title="Plano">
                                            <img src="<?=url("images/plano_planta_palmerajunior.png") ?>"
                                                style="width:100%;display: initial;margin: 0 auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" />
                                        </a>
                                    </div>
                                </div>

                                <!-- plano de mecanismos -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-2 plano-md">
                                    <h5 class="card-title">Plano de <br> mecanismos</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/img_plano_mecanismo.jpg") ?>"
                                            data-lightbox="images" data-title="Plano mecanismo instalados">
                                            <img src="<?=url("images/img_plano_mecanismo.jpg") ?>"
                                                style="width: 100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de rutas -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-3 plano-md">
                                    <h5 class="card-title">Plano de <br> Rutas</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/img_plano_planta_rutas.jpg") ?>"
                                            data-lightbox="images" data-title="Plano rutas">
                                            <img src="<?=url("images/img_plano_planta_rutas.jpg") ?>"
                                                style="width:100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de riesgos -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-4 plano-md">
                                    <h5 class="card-title">Plano de <br> Riesgos</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/img_plano_planta_riesgos.jpg") ?>"
                                            data-lightbox="images" data-title="Plano riesgos">
                                            <img src="<?=url("images/img_plano_planta_riesgos.jpg") ?>"
                                                style="width:100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>
                            </div>

                            <!-- star service -->

                            <div class="row match-height fileControls-1 display-hidden">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="card-header">
                                        <h4 class="card-title">Servicios</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item display-hidden serviceControls-1">
                                                    <a class="nav-link active" id="baseIcon-tab1" data-toggle="tab"
                                                        aria-controls="tabIcon1" href="#tabIcon4"
                                                        aria-expanded="true">Desratización <i data-toggle="tooltip"
                                                            data-placement="top" title="Editar" class="icon-pencil"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                            data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                            class="icon-close"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i></a>
                                                </li>
                                                <li class="nav-item display-hidden serviceControls-1">
                                                    <a class="nav-link" id="baseIcon-tab2" data-toggle="tab"
                                                        aria-controls="tabIcon2" href="#tabIcon5"
                                                        aria-expanded="false">Desinfección <i data-toggle="tooltip"
                                                            data-placement="top" title="Editar" class="icon-pencil"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                            data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                            class="icon-close"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i></a>
                                                </li>
                                                <li class="nav-item display-hidden serviceControls-1">
                                                    <a class="nav-link" id="baseIcon-tab3" data-toggle="tab"
                                                        aria-controls="tabIcon3" href="#tabIcon6"
                                                        aria-expanded="false">Desinsectación <i data-toggle="tooltip"
                                                            data-placement="top" title="Editar" class="icon-pencil"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                            data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                            class="icon-close"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i></a>
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
                                                                <h4 class="modal-title white" id="myModalLabel9">Crear
                                                                    servicio</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div
                                                                        class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <h5 for="priority">Servicio</h5>
                                                                        <select class="form-control" name="service"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1">Desratización</option>
                                                                            <option value="2">Desinfección</option>
                                                                            <option value="3">MIP</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <label for="price">Área</label>
                                                                        <select class="form-control" name="area"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1" data-price="100000">0
                                                                                m&sup2; - 99 m&sup2;</option>
                                                                            <option value="2" data-price="200000">100
                                                                                m&sup2; - 199 m&sup2;</option>
                                                                            <option value="3" data-price="500000">200
                                                                                m&sup2; - 499 m&sup2;</option>
                                                                            <option value="4" data-price="900000">500
                                                                                m&sup2; - 1000 m&sup2;</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <h5 for="priority">Área de aplicación</h5>
                                                                        <select class="select2 form-control" name="service"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1">Sala</option>
                                                                            <option value="2">Comedor</option>
                                                                            <option value="3">Sotano</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <h5 for="price">Precio</h5>
                                                                        <div class="input-group mb-2" name="cost">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">$
                                                                                </div>
                                                                            </div>
                                                                            <input type="number" class="form-control"
                                                                                name="price" id="inlineFormInputGroup"
                                                                                placeholder="Precio">
                                                                        </div>
                                                                    </div>



                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                                                        <h5 for="area">Periodicidad</h5>

                                                                        <select class="form-control" name="periodicity"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1">Diario</option>
                                                                            <option value="2">Semanal</option>
                                                                            <option value="3">Quicenal</option>
                                                                            <option value="4">Mensual</option>
                                                                            <option value="5">Anual</option>
                                                                            <option value="5">Bajo demanda</option>
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
                                            </ul>
                                            <hr>
                                            <div class="tab-content px-1 pt-3">
                                                <div role="tabpanel" class="tab-pane active" id="tabIcon4"
                                                    aria-expanded="true" aria-labelledby="baseIcon-tab4">
                                                    <div class="row">
                                                        <div
                                                            class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 display-hidden serviceControls-1">
                                                            <h5><b>Servicio:</b>Desratización</h5>
                                                            <h5><b>Área :</b> 0m - 99m</h5>
                                                            <h5><b>Precio :</b> $100.000</h5>
                                                            <h5><b>Periodicidad:</b> Quicenal</h5>
                                                        </div>
                                                    </div>
                                                    <hr>
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
                                                                                                <div
                                                                                                    class="custom-file">
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
                                                                                <h5 for="area">Observación dirigida a:
                                                                                </h5>

                                                                                <select class="form-control"
                                                                                    name="observation">
                                                                                    <option value="01">Administrador
                                                                                    </option>
                                                                                    <option value="02">Comerciales
                                                                                    </option>
                                                                                    <option value="03">Programación
                                                                                    </option>
                                                                                    <option value="04">Compras</option>
                                                                                    <option value="05">Jefe de compras
                                                                                    </option>
                                                                                    <option value="06">Jefe de
                                                                                        programacion</option>
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
                                                                                <h5 for="name">Observaciones del
                                                                                    servicio</h5>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->

                                                    </div>

                                                    <hr>
                                                    <!-- star materials -->

                                                    <div clas="row">
                                                        <h4 class="col-md-12 mb-1 display-hidden serviceControls-1">
                                                            Insumos para Desratización</h4>
                                                        <!-- star fieldset -->
                                                        <fieldset class="fieldspace display-hidden serviceControls-1">
                                                            <div class="repeater-default">
                                                                <div data-repeater-list="car">
                                                                    <div data-repeater-item>
                                                                        <div class="row">
                                                                            <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                                                                <h5>Control</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">Cucarachas
                                                                                        </option>
                                                                                        <option value="02">Ratones</option>
                                                                                        <option value="03">Gusano
                                                                                        </option>
                                                                                        <option value="01">Mariposas</option>
                                                                                        <option value="02">Zancudos</option>
                                                                                        <option value="02">Bacterias</option>
                                                                                </select>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                                                <h5>Sustancia/Mecanismo</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                    <optgroup label="Sustencia">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">CIPERMETRINA
                                                                                        </option>
                                                                                        <option value="02">AGUA</option>
                                                                                        <option value="03">FIPRONIL
                                                                                        </option>
                                                                                    </optgroup>
                                                                                    <optgroup label="Mecanismo">
                                                                                        <option value="01">TRAMPAS DE
                                                                                            CAPTURA</option>
                                                                                        <option value="02">LAMINA
                                                                                            ADHESIVA</option>
                                                                                        <option value="02">LAMINA CON
                                                                                            FEROMONA</option>
                                                                                    </optgroup>



                                                                                </select>
                                                                            </div>

                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1">
                                                                                <label>Dosis</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                                <label>Cantidad</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                                                                <h5 for="area">Aplicacion:</h5>

                                                                                <select class="form-control"
                                                                                    name="Aplication">
                                                                                    <option value="01">Asperción
                                                                                    </option>
                                                                                    <option value="02">Gel</option>
                                                                                    <option value="03">Insuflador
                                                                                    </option>
                                                                                    <option value="04">Nebulización
                                                                                    </option>
                                                                                    <option value="05">Inyección
                                                                                    </option>
                                                                                    <option value="06">Trampas de
                                                                                        captura
                                                                                    </option>
                                                                                    <option value="07">Cebos</option>
                                                                                    <option value="08">Brochado</option>
                                                                                    <option value="09">Lamparas</option>
                                                                                    <option value="10">Espolvoreo
                                                                                    </option>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->
                                                    </div>

                                                    <!-- End materials -->
                                                </div>
                                                <div class="tab-pane" id="tabIcon5" aria-labelledby="baseIcon-tab2">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                            <h5>Servicio: Desinfección</h5>
                                                            <h5>Área : 100m - 200m</h5>
                                                            <h5>Precio : $200.000</h5>
                                                            <h5>Periodicidad: Mensual</h5>
                                                        </div>
                                                    </div>
                                                    <hr>
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
                                                                                                <div
                                                                                                    class="custom-file">
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
                                                                                <h5 for="area">Observación dirigida a:
                                                                                </h5>

                                                                                <select class="form-control"
                                                                                    name="observation">
                                                                                    <option value="01">Administrador
                                                                                    </option>
                                                                                    <option value="02">Comerciales
                                                                                    </option>
                                                                                    <option value="03">Programación
                                                                                    </option>
                                                                                    <option value="04">Compras</option>
                                                                                    <option value="05">Jefe de compras
                                                                                    </option>
                                                                                    <option value="06">Jefe de
                                                                                        programacion</option>
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
                                                                                <h5 for="name">Observaciones del
                                                                                    servicio</h5>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->

                                                    </div>
                                                    <hr>
                                                    <!-- star materials -->

                                                    <div clas="row">
                                                        <h4 class="col-md-12 mb-1">Insumos para Desinfección</h4>
                                                        <!-- star fieldset -->
                                                        <fieldset class="fieldspace">
                                                            <div class="repeater-default">
                                                                <div data-repeater-list="car">
                                                                    <div data-repeater-item>
                                                                        <div class="row">
                                                                        <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                                                                <h5>Control</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">Cucarachas
                                                                                        </option>
                                                                                        <option value="02">Ratones</option>
                                                                                        <option value="03">Gusano
                                                                                        </option>
                                                                                        <option value="01">Mariposas</option>
                                                                                        <option value="02">Zancudos</option>
                                                                                        <option value="02">Bacterias</option>
                                                                                </select>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                                                <h5>Sustancia/Mecanismo</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                    <optgroup label="Sustencia">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">CIPERMETRINA
                                                                                        </option>
                                                                                        <option value="02">AGUA</option>
                                                                                        <option value="03">FIPRONIL
                                                                                        </option>
                                                                                    </optgroup>
                                                                                    <optgroup label="Mecanismo">
                                                                                        <option value="01">TRAMPAS DE
                                                                                            CAPTURA</option>
                                                                                        <option value="02">LAMINA
                                                                                            ADHESIVA</option>
                                                                                        <option value="02">LAMINA CON
                                                                                            FEROMONA</option>
                                                                                    </optgroup>



                                                                                </select>
                                                                            </div>

                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1">
                                                                                <label>Dosis</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                                <label>Cantidad</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                                                                <h5 for="area">Aplicacion:</h5>

                                                                                <select class="form-control"
                                                                                    name="Aplication">
                                                                                    <option value="01">Asperción
                                                                                    </option>
                                                                                    <option value="02">Gel</option>
                                                                                    <option value="03">Insuflador
                                                                                    </option>
                                                                                    <option value="04">Nebulización
                                                                                    </option>
                                                                                    <option value="05">Inyección
                                                                                    </option>
                                                                                    <option value="06">Trampas de
                                                                                        captura
                                                                                    </option>
                                                                                    <option value="07">Cebos</option>
                                                                                    <option value="08">Brochado</option>
                                                                                    <option value="09">Lamparas</option>
                                                                                    <option value="10">Espolvoreo
                                                                                    </option>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->
                                                    </div>

                                                    <!-- End materials -->
                                                </div>
                                                <div class="tab-pane" id="tabIcon6" aria-labelledby="baseIcon-tab3">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                            <h5>Servicio: Desinsectación</h5>
                                                            <h5>Área : 300m - 400m</h5>
                                                            <h5>Precio : $300.000</h5>
                                                            <h5>Periodicidad: Bajo demanda</h5>
                                                        </div>
                                                    </div>
                                                    <hr>
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
                                                                                                <div
                                                                                                    class="custom-file">
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
                                                                                <h5 for="area">Observación dirigida a:
                                                                                </h5>

                                                                                <select class="form-control"
                                                                                    name="observation">
                                                                                    <option value="01">Administrador
                                                                                    </option>
                                                                                    <option value="02">Comerciales
                                                                                    </option>
                                                                                    <option value="03">Programación
                                                                                    </option>
                                                                                    <option value="04">Compras</option>
                                                                                    <option value="05">Jefe de compras
                                                                                    </option>
                                                                                    <option value="06">Jefe de
                                                                                        programacion</option>
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
                                                                                <h5 for="name">Observaciones del
                                                                                    servicio</h5>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->

                                                    </div>
                                                    <hr>
                                                    <!-- star materials -->

                                                    <div clas="row">
                                                        <h4 class="col-md-12 mb-1">Insumos para Desinsectación</h4>
                                                        <!-- star fieldset -->
                                                        <fieldset class="fieldspace">
                                                            <div class="repeater-default">
                                                                <div data-repeater-list="car">
                                                                    <div data-repeater-item>
                                                                        <div class="row">
                                                                        <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                                                                <h5>Control</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">Cucarachas
                                                                                        </option>
                                                                                        <option value="02">Ratones</option>
                                                                                        <option value="03">Gusano
                                                                                        </option>
                                                                                        <option value="01">Mariposas</option>
                                                                                        <option value="02">Zancudos</option>
                                                                                        <option value="02">Bacterias</option>
                                                                                </select>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                                                <h5>Sustancia/Mecanismo</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                    <optgroup label="Sustencia">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">CIPERMETRINA
                                                                                        </option>
                                                                                        <option value="02">AGUA</option>
                                                                                        <option value="03">FIPRONIL
                                                                                        </option>
                                                                                    </optgroup>
                                                                                    <optgroup label="Mecanismo">
                                                                                        <option value="01">TRAMPAS DE
                                                                                            CAPTURA</option>
                                                                                        <option value="02">LAMINA
                                                                                            ADHESIVA</option>
                                                                                        <option value="02">LAMINA CON
                                                                                            FEROMONA</option>
                                                                                    </optgroup>



                                                                                </select>
                                                                            </div>

                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1">
                                                                                <label>Dosis</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                                <label>Cantidad</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                                                                <h5 for="area">Aplicacion:</h5>

                                                                                <select class="form-control"
                                                                                    name="Aplication">
                                                                                    <option value="01">Asperción
                                                                                    </option>
                                                                                    <option value="02">Gel</option>
                                                                                    <option value="03">Insuflador
                                                                                    </option>
                                                                                    <option value="04">Nebulización
                                                                                    </option>
                                                                                    <option value="05">Inyección
                                                                                    </option>
                                                                                    <option value="06">Trampas de
                                                                                        captura
                                                                                    </option>
                                                                                    <option value="07">Cebos</option>
                                                                                    <option value="08">Brochado</option>
                                                                                    <option value="09">Lamparas</option>
                                                                                    <option value="10">Espolvoreo
                                                                                    </option>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->
                                                    </div>

                                                    <!-- End materials -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- End service -->


                        </div>
                        <div class="tab-pane" id="tabIcon2" aria-labelledby="baseIcon-tab2">
                            <div class="row">

                                <!-- titulo del area -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mapControls-1 display-hidden">
                                    <h5> <b>Sucursal:</b> Itaguí</h5>

                                </div>

                                <!-- plano de carga con los botenes de opciones -->
                                <div
                                    class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mapControls-1 my-1 display-hidden">
                                    <h5 class="card-title">Plano</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="modal" data-target="#drawMapMecanismo" data-toggle="tooltip"
                                            data-placement="top" title="Ubicar Mecanismo" class="icon-pin"
                                            data-toggle="modal" data-target="#success"
                                            onclick="showMapControls(2);setBgImage();"></i>
                                        <i data-toggle="modal" data-target="#drawMaprutas" data-toggle="tooltip"
                                            data-placement="top" title="Ubicar Rutas" class="icon-cursor-move"
                                            onclick="showMapControls(3);setBgImage();"></i>
                                        <i data-toggle="modal" data-target="#drawMaprutas" data-toggle="tooltip"
                                            data-placement="top" title="Señalar Riesgos" class="icon-fire"
                                            onclick="showMapControls(4);setBgImage();"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano">
                                        <a href="<?=url("images/plano_planta_palmerajunior.png") ?>"
                                            data-lightbox="images" data-title="Plano">
                                            <img src="<?=url("images/plano_planta_palmerajunior.png") ?>"
                                                style="width:100%;display: initial;margin: 0 auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de mecanismos -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-2 plano-md">
                                    <h5 class="card-title">Plano de <br> mecanismos</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/plano_mecanismo.jpg") ?>" data-lightbox="images"
                                            data-title="Plano mecanismo instalados">
                                            <img src="<?=url("images/plano_mecanismo.jpg") ?>"
                                                style="width: 100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de rutas -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-3 plano-md">
                                    <h5 class="card-title">Plano de <br> Rutas</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/plano-rutas.png") ?>" data-lightbox="images"
                                            data-title="Plano rutas">
                                            <img src="<?=url("images/img_plano_planta_rutas.jpg") ?>"
                                                style="width:100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de riesgos -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-4 plano-md">
                                    <h5 class="card-title">Plano de <br> Riesgos</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/img_plano_planta_riesgos.jpg") ?>"
                                            data-lightbox="images" data-title="Plano riesgos">
                                            <img src="<?=url("images/img_plano_planta_riesgos.jpg") ?>"
                                                style="width:100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                            </div>

                            <!-- star service -->

                            <div class="row match-height">
                                <div class="col-xl-12 col-lg-12">
                                    <div class="card-header">
                                        <h4 class="card-title">Servicios</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="baseIcon-tab1" data-toggle="tab"
                                                        aria-controls="tabIcon1" href="#tabIcon7"
                                                        aria-expanded="true">Inmunización <i data-toggle="tooltip"
                                                            data-placement="top" title="Editar" class="icon-pencil"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                            data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                            class="icon-close"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="baseIcon-tab2" data-toggle="tab"
                                                        aria-controls="tabIcon2" href="#tabIcon8"
                                                        aria-expanded="false">Encarpado <i data-toggle="tooltip"
                                                            data-placement="top" title="Editar" class="icon-pencil"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                            data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                            class="icon-close"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="modal" data-target="#success3"><i
                                                            class="ft-plus-circle"></i> Agregar</a>
                                                </li>



                                                <!-- Modal -->
                                                <div class="modal fade text-left" data-backdrop="static" id="success3"
                                                    tabindex="-1" role="dialog" aria-labelledby="myModalLabel9"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success white">
                                                                <h4 class="modal-title white" id="myModalLabel9">Crear
                                                                    Servicio</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div
                                                                        class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <h5 for="priority">Servicio</h5>
                                                                        <select class="form-control" name="service"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1">Desratización</option>
                                                                            <option value="2">Desinfección</option>
                                                                            <option value="3">Desinsectación</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                                                        <label for="price">Área</label>
                                                                        <br>
                                                                        <select class="form-control" name="area"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1" data-price="100000">0
                                                                                m&sup2; - 99 m&sup2;</option>
                                                                            <option value="2" data-price="200000">100
                                                                                m&sup2; - 199 m&sup2;</option>
                                                                            <option value="3" data-price="500000">200
                                                                                m&sup2; - 499 m&sup2;</option>
                                                                            <option value="4" data-price="900000">500
                                                                                m&sup2; - 1000 m&sup2;</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <h5 for="price">Precio</h5>
                                                                        <div class="input-group mb-2" name="cost">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">$
                                                                                </div>
                                                                            </div>
                                                                            <input type="number" class="form-control"
                                                                                name="price" id="inlineFormInputGroup"
                                                                                placeholder="Precio">
                                                                        </div>
                                                                    </div>



                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                                                        <h5 for="area">Periodicidad</h5>

                                                                        <select class="form-control" name="periodicity"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1">Diario</option>
                                                                            <option value="2">Semanal</option>
                                                                            <option value="3">Quicenal</option>
                                                                            <option value="4">Mensual</option>
                                                                            <option value="5">Anual</option>
                                                                            <option value="5">Bajo demanda</option>
                                                                        </select>
                                                                    </div>

                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-12 col-lg-12 col-lg-12">
                                                                        <h5 for="name">Observaciones</h5>
                                                                        <textarea class="form-control"
                                                                            name="observation"
                                                                            placeholder="Observaciones del servicio"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn grey btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <button type="button" data-dismiss="modal"
                                                                        class="btn btn-success">Guardar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </ul>
                                            <hr>
                                            <div class="tab-content px-1 pt-3">
                                                <div role="tabpanel" class="tab-pane active" id="tabIcon7"
                                                    aria-expanded="true" aria-labelledby="baseIcon-tab1">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                            <h5>Servicio: Inmunización</h5>
                                                            <h5>Área : 0m - 99m</h5>
                                                            <h5>Precio : $100.000</h5>
                                                            <h5>Periodicidad: Quicenal</h5>
                                                            <h5>Observaciones: Ninguna.</h5>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <!-- star materials -->

                                                    <div clas="row">
                                                        <h4 class="col-md-12 mb-1">Insumos Inmunización</h4>
                                                        <!-- star fieldset -->
                                                        <fieldset class="fieldspace">
                                                            <div class="repeater-default">
                                                                <div data-repeater-list="car">
                                                                    <div data-repeater-item>
                                                                        <div class="row">
                                                                        <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                                                                <h5>Control</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">Cucarachas
                                                                                        </option>
                                                                                        <option value="02">Ratones</option>
                                                                                        <option value="03">Gusano
                                                                                        </option>
                                                                                        <option value="01">Mariposas</option>
                                                                                        <option value="02">Zancudos</option>
                                                                                        <option value="02">Bacterias</option>
                                                                                </select>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                                                <h5>Sustancia/Mecanismo</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                    <optgroup label="Sustencia">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">CIPERMETRINA
                                                                                        </option>
                                                                                        <option value="02">AGUA</option>
                                                                                        <option value="03">FIPRONIL
                                                                                        </option>
                                                                                    </optgroup>
                                                                                    <optgroup label="Mecanismo">
                                                                                        <option value="01">TRAMPAS DE
                                                                                            CAPTURA</option>
                                                                                        <option value="02">LAMINA
                                                                                            ADHESIVA</option>
                                                                                        <option value="02">LAMINA CON
                                                                                            FEROMONA</option>
                                                                                    </optgroup>



                                                                                </select>
                                                                            </div>

                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1">
                                                                                <label>Dosis</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                                <label>Cantidad</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                                                                <h5 for="area">Aplicacion:</h5>

                                                                                <select class="form-control"
                                                                                    name="Aplication">
                                                                                    <option value="01">Asperción
                                                                                    </option>
                                                                                    <option value="02">Gel</option>
                                                                                    <option value="03">Insuflador
                                                                                    </option>
                                                                                    <option value="04">Nebulización
                                                                                    </option>
                                                                                    <option value="05">Inyección
                                                                                    </option>
                                                                                    <option value="06">Trampas de
                                                                                        captura
                                                                                    </option>
                                                                                    <option value="07">Cebos</option>
                                                                                    <option value="08">Brochado</option>
                                                                                    <option value="09">Lamparas</option>
                                                                                    <option value="10">Espolvoreo
                                                                                    </option>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->
                                                    </div>

                                                    <!-- End materials -->
                                                </div>
                                                <div class="tab-pane" id="tabIcon8" aria-labelledby="baseIcon-tab2">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                            <h5>Servicio: Encarpado</h5>
                                                            <h5>Área : 100m - 200m</h5>
                                                            <h5>Precio : $200.000</h5>
                                                            <h5>Periodicidad: Mensual</h5>
                                                            <h5>Observaciones: Ninguna.</h5>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <!-- star materials -->

                                                    <div clas="row">
                                                        <h4 class="col-md-12 mb-1">Insumos para encarpado</h4>
                                                        <!-- star fieldset -->
                                                        <fieldset class="fieldspace">
                                                            <div class="repeater-default">
                                                                <div data-repeater-list="car">
                                                                    <div data-repeater-item>
                                                                        <div class="row">
                                                                        <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                                                                <h5>Control</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">Cucarachas
                                                                                        </option>
                                                                                        <option value="02">Ratones</option>
                                                                                        <option value="03">Gusano
                                                                                        </option>
                                                                                        <option value="01">Mariposas</option>
                                                                                        <option value="02">Zancudos</option>
                                                                                        <option value="02">Bacterias</option>
                                                                                </select>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                                                <h5>Sustancia/Mecanismo</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                    <optgroup label="Sustencia">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">CIPERMETRINA
                                                                                        </option>
                                                                                        <option value="02">AGUA</option>
                                                                                        <option value="03">FIPRONIL
                                                                                        </option>
                                                                                    </optgroup>
                                                                                    <optgroup label="Mecanismo">
                                                                                        <option value="01">TRAMPAS DE
                                                                                            CAPTURA</option>
                                                                                        <option value="02">LAMINA
                                                                                            ADHESIVA</option>
                                                                                        <option value="02">LAMINA CON
                                                                                            FEROMONA</option>
                                                                                    </optgroup>



                                                                                </select>
                                                                            </div>

                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1">
                                                                                <label>Dosis</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                                <label>Cantidad</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                                                                <h5 for="area">Aplicacion:</h5>

                                                                                <select class="form-control"
                                                                                    name="Aplication">
                                                                                    <option value="01">Asperción
                                                                                    </option>
                                                                                    <option value="02">Gel</option>
                                                                                    <option value="03">Insuflador
                                                                                    </option>
                                                                                    <option value="04">Nebulización
                                                                                    </option>
                                                                                    <option value="05">Inyección
                                                                                    </option>
                                                                                    <option value="06">Trampas de
                                                                                        captura
                                                                                    </option>
                                                                                    <option value="07">Cebos</option>
                                                                                    <option value="08">Brochado</option>
                                                                                    <option value="09">Lamparas</option>
                                                                                    <option value="10">Espolvoreo
                                                                                    </option>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->
                                                    </div>

                                                    <!-- End materials -->
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- End service -->





                        </div>
                        <div class="tab-pane" id="tabIcon3" aria-labelledby="baseIcon-tab3">
                            <div class="row">

                                <!-- titulo del area -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mapControls-1 display-hidden">
                                    <h5> <b>Sucursal:</b> Estrella</h5>

                                </div>

                                <!-- plano de carga con los botenes de opciones -->
                                <div
                                    class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2 mapControls-1 my-1 display-hidden">
                                    <h5 class="card-title">Plano</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="modal" data-target="#drawMapMecanismo" data-toggle="tooltip"
                                            data-placement="top" title="Ubicar Mecanismo" class="icon-pin"
                                            data-toggle="modal" data-target="#success"
                                            onclick="showMapControls(2);setBgImage();"></i>
                                        <i data-toggle="modal" data-target="#drawMaprutas" data-toggle="tooltip"
                                            data-placement="top" title="Ubicar Rutas" class="icon-cursor-move"
                                            onclick="showMapControls(3);setBgImage();"></i>
                                        <i data-toggle="modal" data-target="#drawMaprutas" data-toggle="tooltip"
                                            data-placement="top" title="Señalar Riesgos" class="icon-fire"
                                            onclick="showMapControls(4);setBgImage();"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano">
                                        <a href="<?=url("images/plano_planta_palmerajunior.png") ?>"
                                            data-lightbox="images" data-title="Plano">
                                            <img src="<?=url("images/plano_planta_palmerajunior.png") ?>"
                                                style="width:100%;display: initial;margin: 0 auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de mecanismos -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-2 plano-md">
                                    <h5 class="card-title">Plano de <br> mecanismos</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/plano_mecanismo.jpg") ?>" data-lightbox="images"
                                            data-title="Plano mecanismo instalados">
                                            <img src="<?=url("images/plano_mecanismo.jpg") ?>"
                                                style="width: 100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de rutas -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-3 plano-md">
                                    <h5 class="card-title">Plano de <br> Rutas</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/plano-rutas.png") ?>" data-lightbox="images"
                                            data-title="Plano rutas">
                                            <img src="<?=url("images/img_plano_planta_rutas.jpg") ?>"
                                                style="width:100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>

                                <!-- plano de riesgos -->

                                <div
                                    class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 my-1 display-hidden mapControls-4 plano-md">
                                    <h5 class="card-title">Plano de <br> Riesgos</h5>
                                    <div class="actionIcono">
                                        <i data-toggle="tooltip" data-placement="top" title="Editar"
                                            class="icon-pencil"></i>
                                        <i data-toggle="tooltip" data-placement="top" title="Eliminar"
                                            class="icon-close"></i>
                                    </div>
                                    <div class="imgPlano-xs imgPlano-md">
                                        <a href="<?=url("images/img_plano_planta_riesgos.jpg") ?>"
                                            data-lightbox="images" data-title="Plano riesgos">
                                            <img src="<?=url("images/img_plano_planta_riesgos.jpg") ?>"
                                                style="width:100%;display: initial;margin: 1px auto;border-radius: 3px;box-shadow: 2px 2px 4px #ccc;" /></a>
                                    </div>
                                </div>


                            </div>

                            <!-- star service -->

                            <div class="row match-height">
                                <div class="col-xl-12 col-lg-12">
                                    <div class="card-header">
                                        <h4 class="card-title">Servicios</h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="baseIcon-tab1" data-toggle="tab"
                                                        aria-controls="tabIcon1" href="#tabIcon4"
                                                        aria-expanded="true">Lavado de tanques <i data-toggle="tooltip"
                                                            data-placement="top" title="Editar" class="icon-pencil"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i><i
                                                            data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                            class="icon-close"
                                                            style="left: 17px;font-size: 15px;position: relative;"></i></a>
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
                                                                <h4 class="modal-title white" id="myModalLabel9">Crear
                                                                    Servicio</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div
                                                                        class="form-group col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <h5 for="priority">Servicio</h5>
                                                                        <select class="form-control" name="service"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1">Desratización</option>
                                                                            <option value="2">Desinfección</option>
                                                                            <option value="3">Desinsectación</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                                                        <label for="price">Área</label>
                                                                        <br>
                                                                        <select class="form-control" name="area"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1" data-price="100000">0
                                                                                m&sup2; - 99 m&sup2;</option>
                                                                            <option value="2" data-price="200000">100
                                                                                m&sup2; - 199 m&sup2;</option>
                                                                            <option value="3" data-price="500000">200
                                                                                m&sup2; - 499 m&sup2;</option>
                                                                            <option value="4" data-price="900000">500
                                                                                m&sup2; - 1000 m&sup2;</option>
                                                                        </select>
                                                                    </div>
                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-6 col-lg-5 col-xl-5">
                                                                        <h5 for="price">Precio</h5>
                                                                        <div class="input-group mb-2" name="cost">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">$
                                                                                </div>
                                                                            </div>
                                                                            <input type="number" class="form-control"
                                                                                name="price" id="inlineFormInputGroup"
                                                                                placeholder="Precio">
                                                                        </div>
                                                                    </div>



                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                                                                        <h5 for="area">Periodicidad</h5>

                                                                        <select class="form-control" name="periodicity"
                                                                            required>
                                                                            <option value="">-Seleccione-</option>
                                                                            <option value="1">Diario</option>
                                                                            <option value="2">Semanal</option>
                                                                            <option value="3">Quicenal</option>
                                                                            <option value="4">Mensual</option>
                                                                            <option value="5">Anual</option>
                                                                            <option value="5">Bajo demanda</option>
                                                                        </select>
                                                                    </div>

                                                                    <div
                                                                        class="form-group mb-1 col-12 col-sm-12 col-md-12 col-lg-12 col-lg-12">
                                                                        <h5 for="name">Observaciones</h5>
                                                                        <textarea class="form-control"
                                                                            name="observation"
                                                                            placeholder="Observaciones del servicio"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn grey btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <button type="button" data-dismiss="modal"
                                                                        class="btn btn-success">Guardar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </ul>
                                            <hr>
                                            <div class="tab-content px-1 pt-3">
                                                <div role="tabpanel" class="tab-pane active" id="tabIcon4"
                                                    aria-expanded="true" aria-labelledby="baseIcon-tab4">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                                            <h5>Servicio: Lavado de tanques</h5>
                                                            <h5>Área : 0m - 99m</h5>
                                                            <h5>Precio : $100.000</h5>
                                                            <h5>Periodicidad: Quicenal</h5>
                                                            <h5>Observaciones: Ninguna.</h5>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <!-- star materials -->

                                                    <div clas="row">
                                                        <h4 class="col-md-12 mb-1">Insumos para Lavado de tanques</h4>
                                                        <!-- star fieldset -->
                                                        <fieldset class="fieldspace">
                                                            <div class="repeater-default">
                                                                <div data-repeater-list="car">
                                                                    <div data-repeater-item>
                                                                        <div class="row">
                                                                        <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-2 col-xl-2">
                                                                                <h5>Control</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">Cucarachas
                                                                                        </option>
                                                                                        <option value="02">Ratones</option>
                                                                                        <option value="03">Gusano
                                                                                        </option>
                                                                                        <option value="01">Mariposas</option>
                                                                                        <option value="02">Zancudos</option>
                                                                                        <option value="02">Bacterias</option>
                                                                                </select>
                                                                            </div>
                                                                            <div
                                                                                class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                                                                <h5>Sustancia/Mecanismo</h5>
                                                                                <select class="select2 form-control"
                                                                                    name="category">
                                                                                    <optgroup label="Sustencia">
                                                                                        <option value="--" selected>
                                                                                            -seleccionar-</option>
                                                                                        <option value="01">CIPERMETRINA
                                                                                        </option>
                                                                                        <option value="02">AGUA</option>
                                                                                        <option value="03">FIPRONIL
                                                                                        </option>
                                                                                    </optgroup>
                                                                                    <optgroup label="Mecanismo">
                                                                                        <option value="01">TRAMPAS DE
                                                                                            CAPTURA</option>
                                                                                        <option value="02">LAMINA
                                                                                            ADHESIVA</option>
                                                                                        <option value="02">LAMINA CON
                                                                                            FEROMONA</option>
                                                                                    </optgroup>



                                                                                </select>
                                                                            </div>

                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1">
                                                                                <label>Dosis</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
                                                                                <label>Cantidad</label>
                                                                                <input type="text" class="form-control">
                                                                            </div>
                                                                            <div
                                                                                class="form-group mb-1 col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                                                                <h5 for="area">Aplicacion:</h5>

                                                                                <select class="form-control"
                                                                                    name="Aplication">
                                                                                    <option value="01">Asperción
                                                                                    </option>
                                                                                    <option value="02">Gel</option>
                                                                                    <option value="03">Insuflador
                                                                                    </option>
                                                                                    <option value="04">Nebulización
                                                                                    </option>
                                                                                    <option value="05">Inyección
                                                                                    </option>
                                                                                    <option value="06">Trampas de
                                                                                        captura
                                                                                    </option>
                                                                                    <option value="07">Cebos</option>
                                                                                    <option value="08">Brochado</option>
                                                                                    <option value="09">Lamparas</option>
                                                                                    <option value="10">Espolvoreo
                                                                                    </option>
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
                                                        </fieldset>
                                                        <!-- End fieldset -->
                                                    </div>

                                                    <!-- End materials -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- End service -->

                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End area -->

</fieldset>




<!-- Modal Crear Plano -->
<div class="modal fade text-left" data-backdrop="static" id="drawMap" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel9" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel9"><i class="ft-edit-3"></i>Crear Plano</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div id="wPaint" class="wPaint "
                    style="position:relative; width:100%; height:500px; background-color:#7a7a7a; margin:70px auto 20px auto;">
                </div>
                <center style="margin-bottom: 50px;">
                </center>
                <center id="wPaint-img"></center>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal"
                    onclick='showMapControls(1);'>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubicar Mecanismo-->
<div class="modal fade text-left" data-backdrop="static" id="drawMapMecanismo" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel9" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel9"><i class="ft-edit-3"></i>Ubicar mecanismo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div>
                    <img src="{{url('')}}/images/plano_mecanismo.jpg">
                </div>
                <!-- <center style="margin-bottom: 50px;" class="wPaint">
                    </center>
                    <center id="wPaint-img"></center> -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal"
                    onclick='showMapControls(1);'>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubicar Rutas-->
<div class="modal fade text-left" data-backdrop="static" id="drawMaprutas" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel9" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel9"><i class="ft-edit-3"></i>Crear Rutas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div>
                    <img src="{{url('')}}/images/image_rutas.jpg">
                </div>
                <!-- <center style="margin-bottom: 50px;" class="wPaint">
                    </center>
                    <center id="wPaint-img"></center> -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal"
                    onclick='showMapControls(1);'>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubicar Rutas-->
<div class="modal fade text-left" data-backdrop="static" id="drawMapriesgos" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel9" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel9"><i class="ft-edit-3"></i>Ubicar Riesgos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div>
                    <img src="{{url('')}}/images/plano_planta_riesgos.jpg">
                </div>
                <!-- <center style="margin-bottom: 50px;" class="wPaint">
                    </center>
                    <center id="wPaint-img"></center> -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal"
                    onclick='showMapControls(1);'>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Planos-->
<div class="modal fade text-left" data-backdrop="static" id="drawFile" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel9" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success white">
                <h4 class="modal-title white" id="myModalLabel9"><i class="ft-edit-3"></i>Planos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div>
                            <h5>Subir Plano</h5>
                        </div>
                        <div class="form-group" name="fileDiagnostic">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01">
                                <label class="custom-file-label" for="inputGroupFile01">Cargar
                                    Plano</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-1 col-2 col-sm-2 col-md-6 col-lg-4 col-xl-4 mb-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-outline-success buttonAdvance " data-toggle="modal"
                                data-target="#drawMap">
                                Crear Plano
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" data-dismiss="modal"
                    onclick='showMapControls(1);'>Guardar</button>
            </div>
        </div>
    </div>
</div>