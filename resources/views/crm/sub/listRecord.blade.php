<?php use Carbon\Carbon; ?>
    <div class="modal-header">
        <h5 class="modal-title">Historial {{$fileExtend->extendible->name}} - N:{{$fileExtend->num}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
        <div class="modal-body" style="padding: 80px;">
            <!-- Formulario de detalle de contacto -->
            <div class="col-12">
                @forelse ($fileExtend->record as $record)
                    <label><span class="text-danger"># {{$record->type->name}}</span>{{$record->observation}} <strong>{{$fileExtend->extendible->name}}</strong> <i>{{$fileExtend->extendible->name}}</i></label>
                    {{-- <label>{{$record->}}</label>
                    <label>{{$record->}}</label> --}}
                @empty
                    <span class="text-danger">Sin reportes</span> 
                @endforelse
            </div>
        </form>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    </div>