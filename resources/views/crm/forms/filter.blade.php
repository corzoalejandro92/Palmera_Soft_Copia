<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filtros</h4>
            <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse" class="collapsed"><i class="ft-minus"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content collapse show">
            <div class="card-body">

                <div class="row">

                    <div class="col-md-12">
                        <form id="crmTrackings">
                        {{ method_field('POST') }} 
                        {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Tipo de evento</h5>
                                    <fieldset class="form-group">
                                        <select class="form-control input-lg" id="filterTypesEvent" name="filterTypesEvent[]" multiple>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4 select-user select-result">
                                    <h5>Usuario responsable</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" id="filterUser" name="filterUser[]" multiple>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <h5>Fecha del evento</h5>
                                    <fieldset class="form-group">
                                        <input type="date" class="form-control input-lg"  name="filterDateEvent">
                                    </fieldset>
                                </div>

                                <div class="col-md-4 select-user select-result">
                                    <h5>Generada por el sistema</h5>
                                    <div class="row skin skin-square">
                                        <div class="col-md-12 col-sm-12">
                                            <fieldset>
                                                <input type="radio" name="GeneratedSystem"  value="1">
                                                <label for="input-radio-11" style="margin-right: 20px;">Si</label>
                                                <input type="radio" name="GeneratedSystem"  value="0" >
                                                <label for="input-radio-11">No</label>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 btn-fix-position">
                                    <button class="btn btn-outline-teal" data-click="sendFilterEvents"><i class="ft-filter"></i>&nbsp;Filtrar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
