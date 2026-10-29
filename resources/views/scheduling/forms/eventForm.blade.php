<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h4 class="modal-title">{{ $schedule->crm->name }} > Control {{ $schedule->service->name }} > Seguimiento</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="eventForm">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $crmTracking->id ?? '' }}" />
        <input type="hidden" name="schedule_id" value="{{ $schedule->id ?? '' }}" />
        <input type="hidden" name="crm_id" value="{{  $schedule->crm->id }}" />
        <input type="hidden" name="office_id" value="{{  $schedule->office->id }}" />
        <input type="hidden" name="enabled" value="1" />
        <div class="row">
            <div class="form-group col-12 col-sm-12 col-md-10 col-lg-4 col-xl-4">
                <h5>Fecha</h5>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="ft-calendar"></span>
                        </span>
                    </div>
                    <input class="form-control datepicker"
                        value="{{ Carbon::parse($crmTracking->date ?? '')->format("Y-m-d")}}" name="date" 
                        type="date" readonly="readonly">
                </div>
            </div>

            <div class="form-group col-sm-12 col-md-3 col-lg-4 col-xl-4">
                <h5>Titulo</h5>
                <input type="text" class="form-control" data-keyup="hiden-error" name="title" value="{{ $crmTracking->title ?? 'Servicio ' . $schedule->service->name  . ' con fecha ' . $schedule->date  }}"
                    placeholder="Ingrese el titulo">
            </div>

            <div class="form-group col-sm-12 col-md-3 col-lg-4 col-xl-4">
                <h5>Tipo de evento</h5>
                    <select class="form-control crmTrackingType" name="crm_tracking_type_id" data-change="hiden-error">
                        <option value="">--seleccionar--</option>
                        <?php if (!empty($crmTracking->crmTrackingType)): ?>
                        <option value="<?= $crmTracking->crmTrackingType->id; ?>" selected=""><?= $crmTracking->crmTrackingType->name; ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <h5>Detalle</h5>
                <textarea type="text" name="detail" data-keyup="hiden-error" class="form-control"
                    placeholder="Ingrese el detalle de evento"><?=$crmTracking->detail ?? '' ?></textarea>
            </div>
        </div></form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cancelar</button>
    <button type="button" class="btn btn-success round" data-click="saveEvent">Guardar Seguimiento</button>
</div>

