<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Valor</th>
            <th class="text-center">Año</th>
            <th class="text-center">Tipo de meta</th>
            <th class="nofilter text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        @php
            $cont=1;
        @endphp
        @foreach ($objetives as $objetive)
        <tr>
            <td class="text-center">{{ $cont++ }}</td>
            <td class="text-center">{{  number_format($objetive->value,0,'.',',') }}</td>
            <td class="text-center">{{ $objetive->year}}</td>
            <td class="text-center">{{ $objetive->type->name}}</td>
            <td class="text-center">
                <div class="text-center">
                    @can('objetive.edit')
                        <a href="#" data-click="openForm" data-id="<?= $objetive->id ?>" class="text-success"><i class="las la-edit la-2x"></i></i></a>
                    @endcan
                    @can('objetive.destroy')
                        <a href="#" data-click="openFormDelete" data-id="<?= $objetive->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @php
        @endphp
        @endforeach

    </tbody>
</table>