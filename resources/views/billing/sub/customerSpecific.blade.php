<form action="">
    <fieldset class="fieldspace">
        <div class="repeater-default">
            <div data-repeater-list="car">
                <div data-repeater-item>
                    <div class="row">
                        <div class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-2 col-xl-2">
                            <h5 for="area">Cliente</h5>

                            <select class=" select2 form-control" name="periodicity" required>
                                <option value="">-Seleccione-</option>
                                <option value="1">Solla</option>
                                <option value="2">Zenu</option>
                                <option value="3">Noel</option>
                                <option value="4">Exito</option>
                                <option value="5">Bavaria</option>
                            </select>
                        </div>
                        <div class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-2 col-xl-2">
                            <h5 for="area">Mes</h5>

                            <select class=" select2 form-control" name="periodicity" required>
                                <option value="">-Seleccione-</option>
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-2 col-xl-2">
                            <h5 for="area">Dia</h5>

                            <select class=" select2 form-control" name="periodicity" required>
                                <option value="">-Seleccione-</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                            </select>
                        </div>
                        <div class="form-group mb-1 col-12 col-sm-12 col-md-5 col-lg-2 col-xl-2">
                            <h5 for="area">Dia de la semana</h5>

                            <select class=" select2 form-control" name="periodicity" required>
                                <option value="">-Seleccione-</option>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miercoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sabado</option>
                            </select>
                        </div>
                        <div class="row match-height">
                            <div>
                                <div class="card-body" style="top:-18px;">
                                    <h5>Enviar correo</h5>
                                    <div class="row icheck_minimal skin" >
                                        <div class="col-12 col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                            <fieldset>
                                                <input type="checkbox" value="1">
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 send-email display-hidden">
                            <h5>Correo</h5>
                            <input type="text" class="form-control"
                                placeholder="Ingrese el correo electronico">
                        </div>
                        <div class="form-group col-10 col-sm-10 col-md-10 col-lg-10 col-lg-10">
                            <h5 for="name">Observaciones</h5>
                            <textarea class="form-control" name="observation"
                                placeholder="Ingrese las observaciones generales"></textarea>
                        </div>

                        <div class="form-group col-2 col-sm-2 col-md-2 text-center mt-1">
                            <button type="button" class="btn btn-sm btn-round btn-fab top-5 btn-danger"
                                data-repeater-delete>
                                <i class="ft-x"></i>
                            </button>
                        </div>
                    </div>
                    <hr style="border-top: 1px dashed #a4aab6;">
                </div>
                <div class="form-group overflow-hidden">
                </div>
            </div>
            <div class="col-12">
                <a data-repeater-create class="btn btn-sm btn-round btn-fab btn-success text-white">
                    <i class="ft-plus"></i>
                </a>
            </div>
        </div>
    </fieldset>
</form>