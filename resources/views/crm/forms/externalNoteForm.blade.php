<?php use Carbon\Carbon; ?>
<div class="modal-header">
    @php $titleType = $externalNote->type ?? $type; @endphp
    <h5 class="modal-title text-uppercase">{{$titleType == '1' ? 'NOTA EXTERNA': 'NOTA DE FACTURACION'}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="formExternalNote">
        {{ method_field(isset($externalNote->id) && $externalNote->id ? 'PUT' :'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{ $externalNote->id ?? '' }}" name="id" />
        <input type="hidden" value="{{ $date }}" name="date" />
        <input type="hidden" value="{{ isset($externalNote->user)? $externalNote->user->id: auth()->user()->id}}" name="user" />
        <input type="hidden" value="{{ isset($externalNote->office)? $externalNote->office->id: $office->id}}" name="office" />
        <input type="hidden" value="{{ isset($externalNote->crm)? $externalNote->crm->id: $crm->id}}" name="crm" />
        <input type="hidden" value="{{ $externalNote->date ?? $date}}" name="date" />
        <input type="hidden" value="{{ $externalNote->type ?? $type}}" name="type" />

        <div class="m-2">
            <div class="details">
                <hr>
                <div>
                    <div class="row">
                        <div class="col-5">
                            <h6>Cliente</h6>
                            <img class="avatar xxs" src="<?= url($crm->avatar ?? '')?>" onerror="this.src='{{url("images/default/customer.png")}}'" /> <span>&nbsp;<?= $crm->name ?></span>
                        </div>
                        @if(isset($office))
                        <div class="col-4">
                            <h6>Sucursal</h6>
                            <img class="avatar xxs" src="{{url("images/default/office.png")}}" /> <span>&nbsp;<?= $office->name ?></span>
                        </div>
                        @endif
                        <div class="col-3">
                            <h6>Fecha</h6>
                            <img class="avatar xxs" src="{{url("images/default/calendar.png")}}" /> <span>&nbsp;<?= $date ?></span>
                        </div>
                        
                    </div>
                </div>
                <hr>
            </div>
        </div>
        @if ($titleType == '2')
            <div class="row m-2">
                <div class="col-6">
                    <div class="form-group">
                        <label for="#">Numero</label>
                        <input type="text" class="form-control" value="{{ $externalNote->number ?? null}}" name="number" placeholder="2560">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="#">Valor</label>
                        <input type="text" class="form-control" value="{{ $externalNote->value ?? null }}"  name="value" placeholder="850000">
                    </div>
                </div>

            </div>
            <div class="row m-2">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Comercial</label>
                            <select class="select2 form-control" name="commercial" data-change="hiden-error">
                                <option value="">--seleccionar--</option>
                                @if ($externalNote && $externalNote->commercial_id)
                            
                                    @php $user = \App\User::withTrashed()->find($externalNote->commercial_id); @endphp
            
                                    @if($user)
                                        <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                    @endif
                                @else
                                    @if ($crm && $crm->user_id)
                                
                                        @php $user = \App\User::withTrashed()->find($crm->user_id); @endphp
                
                                        @if($user)
                                            <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                        @endif
                                    @else
                                        @php $user = Auth::User(); @endphp
                                        <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                    @endif
                                @endif
                            </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="#">Fecha</label>
                        <input type="date" class="form-control" value="{{ isset($externalNote->invoice_date) ? $externalNote->invoice_date : $date }}"  name="invoice_date" >
                    </div>
                </div>

            </div>
            <div class="row m-2">
                <div class="col-12">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="new_service" value="1" {{ isset($externalNote->new_service) ? (($externalNote->new_service == 1) ? "checked" : ""):"" }} id="new_service">
                        <label class="custom-control-label" for="new_service">Servicio Nuevo</label>
                    </div>
                </div>
            </div>
        @endif
            <div class="row m-2">
                <div class="col-12">
                    <div class="form-group">
                        <label for="#">Observaciones<span class="text-danger">*</span></label>
                        <textarea class="form-control" data-keyup="hiden-error" name="observation">{{$externalNote->observation ?? ''}}</textarea>
                    </div>
                </div>
            </div>
        
        @if(isset($externalNote->id))
        <div class="row m-2">
            <div class="col-12">
                <h6> Última modificación</h6>
                <img class="avatar xxs" src="<?= url(isset($externalNote->user) ? $externalNote->user->avatar :  auth()->user()->avatar)?>" onerror="this.src='{{url("images/default/customer.png")}}'" /> <span>&nbsp;<?= isset($externalNote->user)? $externalNote->user->name . ( $externalNote->updated_at ? $externalNote->updated_at->format('\, d \d\e F \d\e\l Y \a \l\a\s h:m a'): '') : auth()->user()->name ?></span>
            </div>
        </div>
        @endif
    </form>
</div>

<div class="modal-footer">
    @if(isset($externalNote->id))
        <button type="button"  style="left: 33px;position: absolute;" class="btn btn-outline-danger round externalNoteDelete" data-id="{{$externalNote->id ?? ''}}">Eliminar</button>
    @endif
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="saveExternalNote">Guardar</button>
</div>

<style>
    .select2-selection__choice {
        width: auto!important
    }
</style>