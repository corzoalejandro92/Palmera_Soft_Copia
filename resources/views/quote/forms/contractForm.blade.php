@if($quote)
<div class="modal-header">
    <h4 class="modal-title">{{ $quote->crm->name }} > Cotización {{ $quote->id }} > Crear Contrato</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="contract">
        {{ method_field( isset($contract->id) ? 'PUT' : 'POST') }}
        {{ csrf_field() }}
        <input type="hidden" name="id" value="<?= isset($contract->id) ? $contract->id :'' ?>" />
        <input type="hidden" name="quote_id" value="<?= isset($quote->id)  ? $quote->id : '' ?>" />
        <input type="hidden" name="crm_id" value="<?=isset($quote->crm_id)  ? $quote->crm_id : '' ?>" />
        <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title">Indique las fechas de vigencia:</h4>
                        <p>Esto solo creará un <code> registro</code> que permitirá al sistema identificar los servicios que contractualmente se podrán programar automáticamente, pero no representa un formato de contrato real.</p>
                        <div class="col-12">
                            <label class="col-form-label">Nro. Contrato</label>
                            <input type="number" class="form-control" data-change="hiden-error" data-keyup="hiden-error" placeholder="Consecutivo" name="number" min="1" placeholder="Ingrese un consecutivo"  value="{{ucwords(isset($contract->start_date) ?  $contract->start_date->format('d F, Y') : $possible_start_date->format('d F, Y'))}}">
                        </div>
                        <div class="col-12">
                            <label class="col-form-label">Fecha de inicio</label>
                            <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="start_date" placeholder="Ingrese una fecha"  value="{{ucwords(isset($contract->start_date) ?  $contract->start_date->format('d F, Y') : $possible_start_date->format('d F, Y'))}}">
                        </div>
                        <div class="col-12">
                            <label class="col-form-label">Fecha de finalización</label>
                            <input type="text" class="form-control pickadate-translations" data-change="hiden-error" placeholder="Fecha" name="end_date" placeholder="Ingrese una fecha"  value="{{ucwords(isset($contract->end_date) ?  $contract->end_date->format('d F, Y') : $possible_end_date->format('d F, Y'))}}">
                        </div>
                        <div class="col-12">
                        	<p><br>El sistema captura la fecha mas extensa de la ejecución de alguno de los servicios de esta cotización, sin embargo puede modificarlo, según criterio o nogociación con el cliente.</p>
                    	</div>
                    </div>
                </div>
            </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveContract">Crear contrato</button>
</div>
@endif
