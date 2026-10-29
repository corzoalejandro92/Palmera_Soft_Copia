<hr>
<h4 class="card-title">Metodos del Producto</h4>

<table class="table stripe tbl-methods">
    <thead>
        <tr class="th_title">
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Dosis</th>
            <th>Descripción</th>
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
        @if(isset($product) && $product->methods)
        @foreach($product->methods as $index => $method)
                @php
                $key = uniqid(); 
                @endphp
                @include('product.forms.frames.method_new_row',compact(['key','method']))
        @endforeach
    @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addRowMethod">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>