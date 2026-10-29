<hr>


<table class="table stripe tbl-documents">
    <thead>
        <tr class="th_title">
            <th>Producto</th>
            <th>Cantidad</th>
            <th style="width: 10px;"></th>
        </tr>
    <tbody>
         @if(isset($convection) && $convection->products)
            @foreach($convection->products as $indx => $product)
                 @php 
                    $key = uniqid(); 
                 @endphp
                 @include('convection.forms.frames.product_new_row',compact(['key','product']))
            @endforeach
        @else
            @php $key = uniqid(); $product = null; @endphp
            @include('convection.forms.frames.product_new_row',compact(['key','product']))
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="1" class="add_row_th" style="width: 10px;">
                <a class="btn btn-outline-teal" data-click="addProductRow">
                    <i class="la la-plus-circle"></i>
                </a>
                @include('layouts/loading/min')
            </th>
            <th colspan="3"></th>
        </tr>
    </tfoot>
</table>
              

