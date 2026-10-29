<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Modificación</th>
            <th class="text-center">Editado por</th>
            <th class="text-center">Estado</th>
            <th class="nofilter text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($doseUnits as $doseUnit)
        <tr>
            <td>{{ $doseUnit->id}}</td>
            <td>{{ $doseUnit->name}}</td>
            <td class="text-center">{!!\Carbon\Carbon::parse($doseUnit->updated_at)->format('d-m-Y')!!}</td>
            <td data-id="<?= $doseUnit->id ?>" >
                <div class="row">
                    <div class="col-3">
                        <img class="avatar xxs mr-1" src="<?= url($doseUnit->user->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                    </div>
                    <div class="col-9">
                        <?= $doseUnit->user->name ?? 'Sin usuario' ?>
                    </div>
                </div>
            </td>
            <td class="text-center"><span class="badge badge-{{$doseUnit->enabled ? 'success' : 'dark'}}">{{$doseUnit->enabled ? 'Activo' : 'Inactivo'}}</span></td>
            <td class="text-center">
                <div class="text-center">
                    @can('unit-doses.edit')
                        <a href="#" data-click="openForm" data-id="<?= $doseUnit->id ?>" class="text-success"><i class="las la-edit la-2x"></i></i></a>
                    @endcan
                    @can('unit-doses.destroy')
                        <a href="#" data-click="openFormDelete" data-id="<?= $doseUnit->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>