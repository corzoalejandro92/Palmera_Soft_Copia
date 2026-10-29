<?php

    use App\General\Menu;

?>

<hr>
<h4 class="card-title">Documentos del técnico</h4>

<table class="table stripe tbl-documents">
    <thead>
        <tr class="th_title">
            <th>Archivo</th>
            <th>Tipo</th>
            <th>Observación</th> 
            @if(Menu::isController('User'))
                <th style="width: 10px;"></th>
            @endif
        </tr>
    <tbody>
         @if(isset($user) && $user->files)
            @foreach($user->files as $indx => $document)
                 @php 
                    $key = uniqid(); 
                 @endphp
                 @include('user.forms.frames.document_new_row',compact(['key','document','filetypes']))
            @endforeach
        @else
            @php $key = uniqid(); $document; @endphp
            @include('user.forms.frames.document_new_row',compact(['key','document','filetypes']))
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addDocumentRow">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>
              

