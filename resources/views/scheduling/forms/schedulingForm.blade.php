<?php use Carbon\Carbon; ?>
<div class="modal-header">
    <h5 class="modal-title"><b>{{ isset($scheduling->id)? 'EDITAR' : 'AGREGAR'}} EVENTO</b></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="scheduling" action="{{isset($scheduling->id)?'scheduling/update':'scheduling/store'}}" enctype="multipart/form-data">
        <!-- valores ocultos del formulario -->
        {{ method_field( isset($scheduling->id)? 'POST' : 'POST') }}  
        {{ csrf_field() }}

        <input type="hidden" value="{{$enabled ?? 1}}" name="enabled" />
        
        @if(isset($capture_date) && $capture_date !== false)
            <input type="hidden" value="{{$capture_date}}" name="capture_date" />
        @endif
        

        @if(isset($scheduling->routes) && $scheduling->routes->count() > 0)
            <div class="alert alert-warning" role="alert">
                Este evento pertenece a una ruta!
          </div>
        @endif

        @if(isset($scheduling->routes) && $scheduling->routes->count() > 0)
            <input type="hidden" value="{{$scheduling->routes->first()->id}}" name="route" />
        @endif

        <input type="hidden" value="<?= $scheduling->id ??'' ?>" name="id" />
        @if(isset($notification))
            <input type="hidden" value="<?= $notification ?>" name="deleteNotification" />
        @endif
            <div class="form-group row">
                <label class="col-4 col-form-label">Titulo*</label>
                <div class="col-8">
                    <input class="typeahead form-control" data-keyup="hiden-error" value="<?= $scheduling->name??'' ?>" name="title" type="text" value="" placeholder="Nombre" autocomplete="no">
                </div>
            </div>
        
            <div class="form-group row">
                <label class="col-4 col-form-label">Tipo*</label>
                <div class="col-8">
                    <select class="form-control select2" data-change="hiden-error" name="type" data-placeholder="Departamento" autocomplete="off">
                        <option value="">Seleccione un tipo</option>
                        <?php if (isset($scheduling->schedulingType)): ?>
                            <option value="<?= $scheduling->schedulingType->id; ?>" selected=""><?= $scheduling->schedulingType->name; ?></option>
                        <?php endif; ?>
                        @if(isset($eventType) && !$scheduling)
                            <option value="<?= $eventType->id; ?>" selected><?= $eventType->name; ?></option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Responsables*</label>
                <div class="col-8">
                    <select class="form-control select2" data-change="hiden-error" name="users[]" data-placeholder="Responsable" autocomplete="no" multiple>
                        <option value="">Al menos un responsable</option>
                        <?php if (isset($scheduling->users)): ?>
                            <?php foreach($scheduling->users as $user) : ?>
                                <option value="<?= $user->id; ?>" selected><?= $user->name; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        @if($user && !$scheduling)
                            <option value="<?= $user->id; ?>" selected><?= $user->name; ?></option>
                        @endif
                    </select>
                </div>
            </div>
            @if(isset($hasRoute) && $hasRoute == true && !isset($scheduling->id) && $origin == 'programmer')
                <div class="alert alert-light" role="alert">
                    <div class="row skin skin-square" style="padding-top: 17px;">
                        <div class="col-1">
                            <input type="checkbox" name="append_to_route" value="1" id="input-checkbox-2" checked>
                        </div>
                        <div class="col-11">
                            <label for="input-checkbox-2"> Incluir este evento en la ruta del <b>{{\Carbon\Carbon::parse($hasRoute->date)->format('d/m/Y')}}</b></label>
                        </div>
                    </div>
                </div>
            @endif
            <div class="form-group row">
                <div class="col-12">
                    <label class="float-right" for="input-checkbox-1" style="line-height: 12px;margin-left: 5px;"> Solo Técnicos</label>
                    <input class="float-right" type="checkbox" name="technical" value="1" id="input-checkbox-1" {{ isset( $scheduling->technical) ? ($scheduling->technical == "1" ? 'checked': ''):''}}>
                            
                </div>
            </div>

            <div class="form-group row event-type-div">
                @if(isset($scheduling->schedulingType->id) &&  $scheduling->schedulingType->id !=  3 )
                    @include('scheduling/forms/frames/event_frame')
                @elseif(isset($scheduling->schedulingType->id) &&  $scheduling->schedulingType->id ==  3)
                    @include('scheduling/forms/frames/reminder_frame')
                @endif

                @if(isset($eventType) && !$scheduling )
                    @include('scheduling/forms/frames/event_frame')
                @endif
            </div>


            <div class="Typeframe">
                @if(isset($scheduling->schedulingType))
                
                    @if($scheduling->schedulingType->id == 0)
                    
                        @include('scheduling.forms.basicForm')
                    @elseif($scheduling->schedulingType->id == 1)
                        @include('scheduling.forms.defaultForm')
                    @elseif($scheduling->schedulingType->id== 2)
                        @include('scheduling.forms.basicForm')
                    @elseif($scheduling->schedulingType->id == 3)
                        @include('scheduling.forms.basicForm')
                    @endif
                @else
                    @include('layouts.loading.full')
                @endif

                @if(isset($eventType) && !$scheduling )
                @include('scheduling.forms.basicForm')
                @endif
            </div>
    
        <button type="submit" hidden></button>
    </form>
</div>
<div class="modal-footer">
    @if(isset($scheduling->id))
        <button type="button" class="btn btn-outline-danger round schedulingDelete" data-id="{{$scheduling->id}}" style="position: absolute;left: 18px;">Eliminar evento</button>
    @endif
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round" data-click="save">{{ isset($scheduling->id)? 'Editar' : 'Agregar'}}</button>
</div>