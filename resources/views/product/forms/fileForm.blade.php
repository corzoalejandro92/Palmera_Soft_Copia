<hr>
<h4 class="card-title">Documentos del Producto</h4>

<table class="table stripe tbl-documents">
    <thead>
        <tr class="th_title">
            <th>Archivo</th>
            <th>Tipo</th>
            <th>Observación</th> 
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
            @if(isset($product) && $product->files)
            @foreach($product->files as $indx => $document)
                    @php 
                    $key = uniqid(); 
                    @endphp
                    @include('product.forms.frames.document_new_row',compact(['key','document','filetypes']))
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addRow">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>