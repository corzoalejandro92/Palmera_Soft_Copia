<div class="modal-header">
    <h5 class="modal-title">{{(isset($crms->id)?'EDITAR CLIENTE':'CREAR CLIENTE')}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
    <script>
    let setList = [];
        let defaultValues = {
            'enabled' : 1
        };
    </script>
    @if(isset($crms->id))
    <script>
        @if (isset($openForm))){
            setList = <?=json_encode($crms->contact->toArray()); ?>;
        @endif
    </script>
    @endif

    


    <div class="modal-body" style="padding: 80px;">
        <form id="customer" action="{{isset($crms->id)?'crm/update':'crm/save'}}"  enctype="multipart/form-data">
        {{ method_field( isset($crms->id)?'POST':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $crms->id ?? ''; ?>" name="id" />

        <!-- Formulario de detalle de contacto -->
        <div class="infoForm">
            
        </div>
  
        <!-- Formulario de detalle de contacto -->
        <div class="contactForm">
            
        </div>
    
        <!-- Formulario de sede -->
        <div class="officeForm">
            
        </div>
    
        <!-- documentos del cliente -->
        <div class="fileForm">

        </div>
    
        <!-- Asignacion de Técnicos -->
        <div class="technicalForm">
            
        </div>
    </form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    @if (isset($crms->id))
        @can('crms.edit')
           <button type="button" class="btn btn-primary round" data-click="{{$data_click}}">Editar</button>
        @endcan
    @else
        @can('crms.create')
        <button type="button" class="btn btn-success round" data-click="{{$data_click}}">Guardar</button>
        @endcan
    @endif
</div>