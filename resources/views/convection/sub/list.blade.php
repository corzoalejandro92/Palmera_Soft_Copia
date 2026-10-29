<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Categoria</th>
            <th>Productos</th>
            <th>Creado por</th>
            <th class="nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($convections as $convection)
        <tr>
            <td data-click="openForm" data-id="<?= $convection->id ?>">{{ $convection->id}}</td>
            <td class="td-avatars" data-click="openForm" data-id="<?= $convection->id ?>">
                <div class="d-flex justify-content-left align-items-center" style="gap:5px;">
                    <div class="convention-md-dot" style="background-color:{{ $convection->color }};"></div>
                    <span>{{ '(' .$convection->slug. ') ' . $convection->name}}</span>
                </div>         
            </td>
            <td data-click="openForm" data-id="<?= $convection->id ?>">{{$convection->category->name ?? ''}}</td>
            <td data-click="openForm" data-id="<?= $convection->id ?>">
                @foreach($convection->products as $product)
                    <span class="badge badge-info"> {{$product->name}} : {{$product->pivot->quantity}} </span>
                @endforeach
            </td>
            <td data-click="openForm" data-id="<?= $convection->id ?>">{{ $convection->user->name }}</td>
            <td>
                <div class="text-center">
                    @can('crms.destroy')
                        <a href="#" data-click="openFormDelete" data-id="<?= $convection->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @endcan
                    @can('crms.show')
                        <a href="#" data-click="openForm" data-id="<?= $convection->id ?>" class="text-success"><i class="las la-edit la-2x"></i></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>