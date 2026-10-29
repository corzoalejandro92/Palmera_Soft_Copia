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

        @foreach ($units as $unit)
        <tr>
            <td>{{ $unit->id}}</td>
            <td>{{ $unit->name}}</td>
            <td class="text-center">{!!\Carbon\Carbon::parse($unit->updated_at)->format('d-m-Y')!!}</td>
            <td data-id="<?= $unit->id ?>" >
                <div class="row">
                    <div class="col-3">
                        <img class="avatar xxs mr-1" src="<?= url($unit->user->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                    </div>
                    <div class="col-9">
                        <?= $unit->user->name ?? 'Sin usuario' ?>
                    </div>
                </div>
            </td>
            <td class="text-center"><span class="badge badge-{{$unit->enabled ? 'success' : 'dark'}}">{{$unit->enabled ? 'Activo' : 'Inactivo'}}</span></td>
            <td class="text-center">
                <div class="text-center">
                    @can('crms.show')
                        <a href="#" data-click="openForm" data-id="<?= $unit->id ?>" class="text-success"><i class="las la-edit la-2x"></i></i></a>
                    @endcan
                    @can('crms.destroy')
                        <a href="#" data-click="openFormDelete" data-id="<?= $unit->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>