<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th class="text-center">Tipo de Documento</th>
            <th class="text-center">Modulo al que Aplica</th>
            <th class="text-center">Estado</th>
            <th class="nofilter text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($documentTypes as $documentType)
        <tr>
            <td>{{$documentType->name}}</td>
            <td>{{$documentType->translate_slug}}</td>
            <td class="text-center"><span class="badge badge-{{$documentType->enabled ? 'success' : 'dark'}}">{{$documentType->enabled ? 'Activo' : 'Inactivo'}}</span></td>
            <td>
                <div class="text-center">
                    @can('documentTypes.show')
                        <a href="#" data-click="openForm" data-id="<?= $documentType->id ?>" class="text-success"><i class="las la-edit la-2x"></i></i></a>
                    @endcan
                    @can('documentTypes.destroy')
                        <a href="#" data-click="openFormDelete" data-id="<?= $documentType->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
