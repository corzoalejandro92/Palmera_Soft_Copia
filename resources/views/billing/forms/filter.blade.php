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
                        <form id="billingFilters">
                        {{ method_field('POST') }} 
                        {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>Clientes</h5>
                                    <fieldset class="form-group">
                                        <select class="form-control input-lg" name="customer[]" multiple>
                                        </select>
                                    </fieldset>
                                </div>

                                <div class="col-md-3">
                                    <h5>Fecha del evento</h5>
                                    <fieldset class="form-group">
                                        <input type="date" class="form-control input-lg"  name="filterDateEvent">
                                    </fieldset>
                                </div>

                                <div class="col-md-1">
                                    <button class="btn btn-outline-teal" data-click="sendFilterEvents" style="top: 26px;position: relative;"><i class="ft-filter"></i>&nbsp;Filtrar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
