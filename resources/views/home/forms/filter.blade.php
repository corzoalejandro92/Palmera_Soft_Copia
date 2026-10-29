
<?php $fcha = date("Y-m-d");?>
<form id="#">
    {{ method_field('POST') }}
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-4">
            <fieldset class="form-group position-relative">
                <select class="select2 form-control input-lg" style="position: relative;top: 26px;">
                    <optgroup label="Presente">
                        <option value="1">Este mes</option>
                        <option value="1">Este año</option>
                    </optgroup>
                    <optgroup label="Anterior">
                        <option value="1">Mes anterior</option>
                        <option value="1">Año anterior</option>
                    </optgroup>
                </select>
            </fieldset>
        </div>
        <div class="col-md-4">
            <h5>Desde</h5>
            <fieldset class="form-group position-relative">
                <input type="date" class="form-control input-lg text-center" value="{{ $fcha}}" name="filterDateEvent">
            </fieldset>
        </div>
        <div class="col-md-4">
            <h5>Hasta</h5>
            <fieldset class="form-group position-relative">
                <input type="date" class="form-control input-lg text-center" value="{{ $fcha}}" name="filterDateEvent">
            </fieldset>
        </div>
        <div class="col-md-4">
            <h5>Técnico</h5>
            <fieldset class="form-group position-relative">
                <select class="form-control  input-lg" name="filterTechnical[]" multiple placeholder="Dejar vacío para todos">
                </select>
            </fieldset>
        </div>
        <div class="col-md-4">
            <h5>Servicio</h5>
            <fieldset class="form-group position-relative">
                <select class="form-control input-lg" name="filterService[]" multiple
                    placeholder="Dejar vacío para todos">
                </select>
            </fieldset>
        </div>
        <div class="col-md-4 select-user select-result">
            <h5>Cliente</h5>
            <fieldset class="form-group position-relative">
                <select class="form-control input-lg" name="filterCustomer[]" multiple
                    placeholder="Dejar vacío para todos">
                </select>
            </fieldset>
        </div>
        <div class="col-md-4">
            <h5>Usuario de programación</h5>
            <fieldset class="form-group position-relative">
                <select class="form-control  input-lg" name="filterProgramming[]" multiple placeholder="Dejar vacío para todos">
                </select>
            </fieldset>
        </div>

        <div class="col-md-3 btn-fix-position">
            <button class="btn btn-outline-teal" style="position: relative;top: 27px;" data-click="sendFilterEvents"><i
                    class="ft-filter"></i>&nbsp;Filtrar</button>
        </div>

    </div>
</form>