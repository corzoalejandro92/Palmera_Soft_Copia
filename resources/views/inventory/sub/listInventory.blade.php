<div class="row card-title">
    <div class="col-md-6">
        <h4 class="card-title top-5">{{$warehouse->name}}</h4>
    </div>
</div>
<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th  class="text-center">Producto</th>
            <th class="text-center nofilter">En stock:</th>
            <th class="text-center">Descripción</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($detailInventorys as $detailInventory): ?>
        <tr class="pointer">
            <td>
                <div class="row">
                    <div class="col-3">
                        <img class="avatar xxs mr-1" src="<?= url($detailInventory->product->avatar ?? '')?>" onerror="this.src='{{url("images/default/product.jpg")}}'" />
                    </div>
                    <div class="col-9 avatar-name">
                        {{$detailInventory->product->name}}
                    </div>
                </div>
            </td>
            <td class="text-center">{{$detailInventory->stock}}</td>
            <td style="width: 60%;" class="text-center">{{$detailInventory->product->description ?? 'Vacio'}}</td>
            
        </tr>
        <?php 
        endforeach;
        ?>
    </tbody>
</table>