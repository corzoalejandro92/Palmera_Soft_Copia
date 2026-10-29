<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title text-uppercase">Añadir Control/Refuerzo</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="FormNew">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{ $crm->id ?? '' }}" name="crm_id" />
        <input type="hidden" value="{{ $date }}" name="date" />
        <div class="m-2">
            <div class="details">
                <hr>
                <div>
                    <div class="row">
                        <div class="col-3">
                            <h6>Cliente</h6>
                            <img class="avatar xxs" src="<?= url($crm->avatar ?? '')?>" onerror="this.src='{{url("images/default/customer.png")}}'" /> <span>&nbsp;<?= $crm->name ?></span>
                        </div>
                        @if(isset($office))
                        <div class="col-3">
                            <h6>Sucursal</h6>
                            <img class="avatar xxs" src="{{url("images/default/office.png")}}" /> <span>&nbsp;<?= $office->name ?></span>
                        </div>
                        @endif
                        @if(isset($quote))
                        <div class="col-3">
                            <h6>Cotización</h6>
                            <img class="avatar xxs" src="{{url("images/default/office.png")}}" /> <span>&nbsp;Cotización #<?= $quote->consecutive ?></span>
                        </div>
                        @endif
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <h3 class="ml-2">A partir de un servicio existente:</h3>
        <div class="row m-2">
            <div class="col-4">
                <label class="col-form-label">Seleccione un origen</label>

                <select class="form-control select2" data-change="hiden-error" name="workschedule" data-crm="{{$crm->id}}"  data-placeholder="Cliente" autocomplete="off">
                    @if (isset($scheduleService->workschedule))
                        <option value="<?= $scheduleService->workschedule->id; ?>" selected ><?= $scheduleService->workschedule->text; ?></option>
                    @endif
                </select>
            </div>
            <div class="col-3">
                <label class="col-form-label">Seleccione un servicio del origen:</label>

                <select class="form-control select2" data-change="hiden-error" name="service"  data-placeholder="Cliente" autocomplete="off">
                    @if (isset($scheduleService->service))
                        <option value="<?= $scheduleService->service->id; ?>" selected ><?= $scheduleService->service->name; ?></option>
                    @endif
                </select>
            </div>

            <div class="col-12"><hr></div>

            <div id="new_schedule_frame" style="width: 100%;"></div>

        </div>

        <h3 class="ml-2">O apartir de un servicio nuevo:</h3>
        <div class="row m-2">

            <div class="col-6">
                <label class="col-form-label">Seleccione un servicio nuevo:</label>

                <select class="form-control select2" data-change="hiden-error" name="newservice"  data-placeholder="Cliente" autocomplete="off">
                    @if (isset($allServices))

                        <option value="<?= $scheduleService->service->id; ?>" selected ><?= $scheduleService->service->name; ?></option>
                    @endif
                </select>
            </div>
            <div class="col-1">
                <label class="col-form-label">&nbsp;</label>
                <a type="button" class="btn btn-primary"  data-click="gotoNewSchedule" style="color:#fff;display: block;"> Continuar</a>
            </div>
            <div class="col-12"><hr></div>

            <div id="new_schedule_frame" style="width: 100%;"></div>

        </div>
    
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round"
        data-click="saveControlFormNew">Guardar</button>
</div>

<style>
    .select2-selection__choice {
        width: auto!important
    }
</style>