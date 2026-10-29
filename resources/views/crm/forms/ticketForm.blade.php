<?php use Illuminate\Support\Facades\Storage; ?>
<div class="modal-header">
    <h5 class="modal-title">Nuevo Ticket</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
<form id="formSaveTicket" enctype="multipart/form-data">
    {{ method_field(filled($id) ? 'PUT' : 'POST')}}
        {{ csrf_field() }}
        <input type="hidden" name="office_id" value="{{$office ?? ''}}">
        <div class="form-group">
            <label for="subject" class="form-group">Asunto<span class="text-danger">*</span></label>
            <input name="subject" data-keyup="hiden-error" type="text" class="form-control" value="{{ $ticket->subject }}" placeholder="Asunto">
        </div>
        <div class="form-group">
            <label for="category_id" class="form-group">Categoria <span class="text-danger">*</span></label>
            <select class="form-control" data-keyup="hiden-error" name="category_id" placeholder="Categoría">
                <option value="" selected disabled>Seleccionar</option>
                @foreach ($categories as $category)
                    @php
                        $selected=$category->id == $ticket->category_id ? 'selected' : '';
                    @endphp
                    <option {{$selected}} value="{{ $category->id }}">{{$category->name}}</option>
                @endforeach

            </select>
        </div>
        <div class="form-group">
            <label for="priority_id" class="form-group">Prioridad<span class="text-danger">*</span></label><br>
            @foreach ($priorities as $priority)
                @php
                    $checked=$priority->id == $ticket->priority_id ? 'checked' : '';
                @endphp
                <div class="custom-control  custom-control-inline">
                    <input {{$checked}} type="radio" id="reviewed{{$priority->id}}" data-keyup="hiden-error" name="priority_id" value="{{$priority->id}}" class="custom-control-input">
                    <label class="custom-control-label" for="reviewed{{$priority->id}}">{{$priority->name}}</label>
                </div>
                
            @endforeach
        </div>
        <div class="form-group">
            <label for="message" class="form-group">Mensaje<span class="text-danger">*</span></label>
            <textarea data-keyup="hiden-error" name="message" class="form-control" placeholder="Mensaje" style="height: 120px;">{{$ticket->message}}</textarea>
        </div>
        <div class="form-group">
            <input class="form-group" type="file" name="file">
            @if ($ticket->file)
                @php
                    $nombreE=explode('#',$ticket->file);
                    $nombre=$nombreE[1];
                @endphp
                <a target="_blank" href="{{ Storage::url($ticket->file) }}" >{{ $nombre }}</a>
            @endif
        </div>
    </div>
</form>

    <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
        @can('ticket.create')
            <button type="button" class="btn btn-success round pull-right" data-click="{{$data_click}}"><i class="fa fa-pencil"></i>{{ filled($id) ? 'Editar' : 'Crear' }}</button>
        @endcan
    </div>