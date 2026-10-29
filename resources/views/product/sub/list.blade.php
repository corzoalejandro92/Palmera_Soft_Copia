<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th  class="nofilter pxmin"></th>
            <th class="px3 50">Cod Foxcon</th>
            <th class="px350">Nombre del producto</th>
            <th>Tipo</th>
            <th>Productos Relacionados</th>
            <th>Color</th>
            <th>Tóxico</th>
            <th>Controles</th>
            <th class="nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($products as $product)
        <tr>
             <td class="pxmin"></td>
             <td>{{ $product->code }}</td>
            <?php  $servicePluck = $product->servicios->pluck('name')->implode(', ')  ?>
            <?php  $controlPluck = $product->infestations->pluck('name')->implode(', ') ?>


            <td data-click="openForm" data-id="<?= $product->id ?>" class="px350">
                <div class="row">
                    <div class="col-12 avatar-name">
                        <img class="avatar xxs mr-1" src="<?= url($product->avatar ?? '') ?>" onerror="this.src='{{url("images/default/product.png")}}'" />
                        <?= $product->name ?>
                        
                    </div>
                </div>
            </td>
            <td>{{$product->categorys->name ?? 'Vacio'}}</td>
            <td> @forelse ($product->products as $relatedProduct)
                <span class="badge badge-primary">{{$relatedProduct->name}}</span>
                @empty
                <i style="color:#ddd">No aplica</i>
                @endforelse
            </td>
            <td>{!!$product->color ? '<div class="divcolor" style="background:'.$product->color.'"></div>' : '<i style="color:#ddd">No aplica</i>'!!}</td>
            <td>{{$product->toxico ? 'Si': 'No'}}</td>
            <td>{{ !empty($controlPluck)? $controlPluck: 'Vacio' }}</td>
            <td>
                <div class="text-center">
                    @can('products.show')
                       <a href="#"><i class="icon-pencil mx-3" data-click="openForm" data-id="<?= $product->id ?>"></i></a>
                    @endcan
                    @can('products.destroy')
                       <a href="#"><i class="icon-close mx-1" data-click="openFormDelete" data-id="<?= $product->id ?>"></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>