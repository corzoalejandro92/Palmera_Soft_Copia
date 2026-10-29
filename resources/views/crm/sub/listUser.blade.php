<table class="table table-striped table-bordered zero-configuration text-inputs-searching" id="tblList">
    <thead>
        <tr>
            <th>Nombre</th>
            <th class="text-center">Correo</th>
            <th class="text-center">Roles</th>
            <th class="text-center">Sede</th>
                <th class="text-center nofilter" >Opciones</th>
        </tr>
    </thead>
     <tfoot>
        <tr>
            <th>Nombre</th>
            <th class="text-center">Correo</th>
            <th class="text-center">Roles</th>
            <th class="text-center">Sede</th>
            <th class="text-center nofilter" >Opciones</th>
        </tr>
    </tfoot>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr class="pointer">
            <td data-click="openForm" data-id="<?= $user->id ?>" style="width: 25%">
                <div class="row">
                    <div class="col-3">
                        <img class="avatar xxs mr-1" src="<?= url($user->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                    </div>
                    <div class="col-9">
                        <?= $user->name ?>
                    </div>
                </div>
            </td>
            <td class="text-center" data-click="openForm" data-id="<?= $user->id ?>"><?= $user->email ?></td>
            <td class="text-center" data-click="openForm" data-id="<?= $user->id ?>">
                {{$user->roles->pluck('name')->implode(', ')}}
            </td>
            <td class="text-center" data-click="openForm" data-id="<?= $user->id ?>"><?= $user->headquarter->name ?></td>
            
            <td class="text-center">
                @if(!$user->roles->contains('slug','customer'))
                    @can('crms.info.admin')
                        <i class="las la-trash la-2x text-danger cursor-pointer"  data-click="openFormDeleteUser" data-id="<?= $user->id ?>" data-user_id="<?= $user->id ?>"></i>
                    @endcan
                    
                @endif
                
                @if($user->roles->contains('slug','technical'))
                    <i class="las la-folder la-2x text-success cursor-pointer" title="Ver documentos" data-click="openFormFileUser" data-id="<?= $user->id ?>" data-user_id="<?= $user->id ?>"></i>

                @endif

            </td>
            
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>