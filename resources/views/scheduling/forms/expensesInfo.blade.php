
<div class="modal-header">
    <h4 class="modal-title">Consumos Reportados</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Lote</th>
                <th>Cant.</th>
                <th>Fecha</th>

                {{-- <th>Bodega</th> --}}
            </tr>
        </thead>
        <tbody>
        @forelse ($expenses as $expense)
            @php
                $quantityUnit = $expense->inventoryDetail->product->unit->name;
            @endphp
        
            <tr>
                <td>{{$expense->inventoryDetail->product->name}}</td>
                <td>{{$expense->inventoryDetail->plot}}</td>
                <td>{{$expense->quantity}} {{$quantityUnit}}</td>
                <td>{{$expense->created_at}}</td>
                {{-- <td>{{$expense->warehouse->name}}</td> --}}
            </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No se han reportado consumos para este servicio</td>
        </tr>
        @endforelse
        </tbody>
    </table>
		
	</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
</div>

