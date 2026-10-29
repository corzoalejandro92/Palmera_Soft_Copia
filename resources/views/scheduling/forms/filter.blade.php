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
                        <form id="schedulingFilters">
                        {{ method_field('POST') }} 
                        {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Tipo de evento</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" name="filterTypes[]" multiple placeholder="Dejar vacío para todos">
                                        </select>
                                    </fieldset>
                                </div>
                                {{-- <div class="col-md-3">
                                    <h5>Cruces disponibles</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" id="LargeSelect" name="crossing">
                                            <option value="users" selected> Usuarios</option>
                                            <option value="crms"> Clientes</option>
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-3 select-user select-result">
                                    <h5>Clientes</h5>
                                    <fieldset class="form-group position-relative">
                                        <select class="form-control input-lg" name="crossingData[]" multiple>
                                        </select>
                                    </fieldset>
                                </div> --}}

                                <div class="col-md-3 btn-fix-position">
                                    <button class="btn btn-secondary" data-click="sendFilters">Filtrar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
