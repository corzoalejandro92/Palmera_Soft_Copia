
<div class="modal-header">
    <h5 class="modal-title">{{isset($qr->id) ? 'DETALLES DEL QR':'QR NO ENCONTRADO'}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="qr" action="{{isset($qr->id)?'qr/update':'qr/store'}}">
        {{ method_field( isset($qr->id)?'PUT':'POST') }} 
        {{ csrf_field() }}
        <input type="hidden" value="<?= $qr->id ?? ''; ?>" name="id" />
        <div class="row">
            
            <div class="col-12 col-lg-4 col-md-4">
                <div align="center">
                    <img style="position:relative;margin-top: 13px;" src="data:image/png;base64, 
                {{ base64_encode(
                    QrCode::format('png')
                    ->size(110)
                    ->margin(0)
                    //->merge(url('/images/default/icon-50@2x.png'), 0.3, true)
                    //->errorCorrection('H')
                    //->style('round')
                    ->generate($qr->token))
                }}"> 
                <span style="font-size: 13px;
                font-family: initial;
                font-weight: 700;
                text-align: center;
                display: block;
                margin-top: 5px;
                color: #000;">{{$qr->token}}<br> Palmera Junior S.A.S</span>
                </div>
            </div>
            <div class="col-12 col-lg-8 co-md-7 mt-2">
                @php  $showall = true; @endphp
                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Estado</label>
                    <div class="col-10">
                        @if($qr->deleted_at && $other_qrs->count() > 0)
                        <span class="badge badge-danger" style="position: relative;top: 8px;"> Reemplazado</span>
                        @elseif($qr->deleted_at)
                               <span class="badge badge-danger" style="position: relative;top: 8px;"> Eliminado</span>
                        @elseif($qr->crm && $qr->office && $qr->extend && $qr->scanBy)
                               <span class="badge badge-info" style="position: relative;top: 8px;"> Usado</span>
                        @elseif(!$qr->crm && !$qr->office && $qr->extend)
                               <span class="badge badge-warning" style="position: relative;top: 8px;">Mal asociado</span>
                               <br><br><span>Se asocio con una versión vieja del app, se requiere actualizar el qr con la nueva versión</span>
                        @elseif($qr->extend && !$qr->scanBy)
                               <span class="badge badge-warning" style="position: relative;top: 8px;">Mal asociado</span>
                               <br><br><span>Falta información del personal que escaneó</span>
                        @else
                        <span class="badge badge-success" style="position: relative;top: 8px;">Sin usar</span>
                         @php $showall = false; @endphp
                        @endif
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Lote</label>
                    <div class="col-10">
                        <input type="text" class="form-control" readonly value="{{$qr->qr_lot_id ?? ''}}">
                    </div>
                </div>
                @if($showall)
                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Sede</label>
                    <div class="col-10">
                        <input type="text" class="form-control" readonly value="{{$qr->office ? $qr->office->headquarter->name : ''}}">
                    </div>
                </div>
                

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Cliente</label>
                    <div class="col-10">
                        <input type="text" class="form-control" readonly value="{{$qr->crm->name ?? ''}}">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Suc</label>
                    <div class="col-10">
                        <input type="text" class="form-control" readonly value="{{$qr->office->name ?? ''}}">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Plano</label>
                    <div class="col-10">
                        <input type="text" class="form-control" readonly value="{{$qr->extend->file->filable->name ?? ''}}">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Fecha</label>
                    <div class="col-10">
                        <input type="text" class="form-control" readonly value="{{$qr->scan_date}}">
                    </div>
                </div>

                <div class="form-group m-form__group row">
                    <label class="col-2 col-form-label">Por</label>
                    <div class="col-10">
                        <input type="text" class="form-control" readonly value="{{$qr->scanBy->name ?? ''}}">
                    </div>
                </div>

                @endif

            </div>
            <div class="col-md-12"><hr></div>
            @if($qr->extend)
                <div class="col-md-12">
                    <h4>Mecanismo Asociado</h4>
                    <span><span style="    background: #000;
                        color: greenyellow;
                        padding: 3px;
                        font-size: 10px;
                        border-radius: 4px;">#{{$qr->extend->num}} {{$qr->extend->extendible->slug}}</span> {{$qr->extend->extendible->name}}</span>
                    <hr>
                </div>
            @endif

            @if($showall)
            <div class="col-md-12">
                <h4>Historial de reemplazo</h4>

                <ul>
                    @forelse ($other_qrs as $oq)
                    <li style="list-style: disc">
                        <span>@if($oq->deleted_at)<b><s>{{$oq->token}}</s></b>@else<b>{{$oq->token}}</b>@endif, el {{$oq->updated_at}} por <i><u>{{$oq->scanBy->name}}</u></i></span>
                    </li>
                @empty
                    <small><b><i> - Este Qr no ha sido reemplazado aún</i></b></small>
                @endforelse
                </ul>
                
            </div>
            @endif
        </div>       
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
</div>
 
