
<hr>
<h5 class="modal-title mb-1">Especificación Detallada</h5>
<div class="container">
    <fieldset class="fieldspace">
        <div class="repeater-default">
            <div data-repeater-list="specificationProduct">
                <div data-repeater-item>
                    <input type="hidden" name="id"/>
                    <input type="hidden" name="enabled" value="1" />
                    <div class="row">
                        <div class="form-group col-12 col-sm-12 col-md-4 col-lg-5 col-xl-5">
                            <h5>Título</h5>
                            <input type="text" class="form-control" name="key"
                                placeholder="Ingrese el nombre de la medida">
                        </div>

                        <div class="form-group col-10 col-sm-12 col-md-4 col-lg-5 col-xl-5">
                            <h5>Valor</h5>
                            <input type="text" class="form-control" name="value"
                                placeholder="Ingrese el valor de la medida">
                        </div>

                        <div class="form-group col-2 col-sm-1 col-md-1 text-center mt-1">
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
</div>