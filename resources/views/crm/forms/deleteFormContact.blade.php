@include('layouts.loading.full')
<div class="modal-header">
    <h5 class="modal-title">ELIMINAR CONTACTO</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="frmDeleteContact" class="steps-validation">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}

        <p style="font-size: 1.4rem;">¿Realmente desea eliminar el contacto <b><?= $contact->name ?></b> ?</p>
        
        @if(!is_null($contact->user))
            <hr class="hr-danger">
            <p class="text-info">
                <b>Este contacto está vinculado a un usuario con acceso a la informacion del/los cliente/s: </b>
            </p>
                <ul>
                    @foreach ($othersAccess as $c)
                        <li>- {{ $c->crm->name ?? '' }}</li>
                    @endforeach
                </ul>
                <p class="text-info">
             ¿Que desea hacer?
            </p>
            <div class="row skin skin-square">
                <div class="col-md-12 col-sm-12">
 
                        <fieldset>
                            <input type="radio" name="action" value="delete_and_keep_user" id="input-radio-1">
                            <label for="input-radio-1" >Eliminar contacto & el acceso a <b>{{ $contact->crm->name }}</b></label>
                        </fieldset>
                    
              
                    @if(count($othersAccess) > 1)
                        <fieldset>
                            <input type="radio" name="action" value="delete_all" id="input-radio-2">
                            <label for="input-radio-2" class="">Eliminar contacto & <b> el acceso a los {{ count($othersAccess) }} clientes</b> </label>
                        </fieldset>
                    @endif
                    <fieldset>
                        <input type="radio" name="action" value="keep_contact" id="input-radio-3" >
                        <label for="input-radio-3" class="">Eliminar el acceso pero <b> mantener el contacto</b> </label>
                    </fieldset>
                </div>
            </div>
            <div class="alert alert-info keep_contact" style="display: none;">
                <p> Si lo que desea hacer es revocar el acceso al cliente pero mantener el contacto haga <u style="cursor: pointer;" data-dismiss="modal" data-click="openEditContact" data-id="{{ $contact->id }}"><b>clic aqui.</b></u></p>
            </div>
            
        @endif

        

        <input type="hidden" value="<?= $contact->id ?? ''; ?>" name="id" />
        <div id="formDeleteContent">
            @include('layouts.loading.full')
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-danger round btnsave" data-click="deleteContact" {{ is_null($contact->user) ? '':'disabled' }}>Confirmar</button>
</div>