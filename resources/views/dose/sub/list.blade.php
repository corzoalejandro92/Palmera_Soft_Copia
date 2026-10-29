<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Inicio Rango</th>
            <th class="text-center">Fin Rango</th>
            <th class="text-center">Unidad</th>
            <th class="text-center">Modificación</th>
            <th class="text-center">Editado por</th>
            <th class="text-center">Sede</th>
            <th class="text-center">Estado</th>
            <th class="nofilter text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($doses as $dose)
        <tr>
            <td>{{ $dose->id}}</td>
            <td class="text-center">{{ $dose->start_range}}</td>
            <td class="text-center">{{ $dose->end_range}}</td>
            <td class="text-center" data-id="<?= $dose->id ?>"><?= $dose->unit->name ?></td>
            <td class="text-center">{!!\Carbon\Carbon::parse($dose->updated_at)->format('d-m-Y')!!}</td>
            <td data-id="<?= $dose->id ?>" >
                <div class="row">
                    <div class="col-3">
                        <img class="avatar xxs mr-1" src="<?= url($dose->user->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                    </div>
                    <div class="col-9">
                        <?= $dose->user->name ?? 'Sin usuario' ?>
                    </div>
                </div>
            </td>
            <td class="text-center" data-id="<?= $dose->id ?>"><?= $dose->headquarter->name ?></td>
            <td class="text-center"><span class="badge badge-{{$dose->enabled ? 'success' : 'dark'}}">{{$dose->enabled ? 'Activo' : 'Inactivo'}}</span></td>
            <td class="text-center">
                <div class="text-center">
                    @can('crms.show')
                        <a href="#" data-click="openForm" data-id="<?= $dose->id ?>" class="text-success"><i class="las la-edit la-2x"></i></i></a>
                    @endcan
                    @can('crms.destroy')
                        <a href="#" data-click="openFormDelete" data-id="<?= $dose->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>