<table class="table table-striped table-bordered zero-configuration" id="tblList" >
    <thead>
        <tr>
            <th>Nombre</th>
            <th class="text-center">Correo</th>
            <th class="text-center">Celular</th>
            <th class="text-center">Roles</th>
            <th class="text-center">Sede</th>
            <th class="text-center" >Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr class="pointer">
                <td data-click="openForm" data-id="<?= $user->id ?>" >
                    <div class="row">
                        <div class="col-3">
                            <img class="avatar xxs mr-1" src="<?=  $user->full_avatar ?? url('images/default/user.jpg' )?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                        </div>
                        <div class="col-9">
                            <?= $user->name ?> 
                        </div>
                    </div>
                </td>
                <td class="text-center" data-click="openForm" data-id="<?= $user->id ?>"><?= $user->email ?></td>
                <td class="text-center" data-click="openForm" data-id="<?= $user->id ?>"><?= $user->phone ?></td>
                <td class="text-center" data-click="openForm" data-id="<?= $user->id ?>">
                   {{$user->roles->pluck('name')->implode(', ')}}
                </td>
                <td class="text-center" data-click="openForm" data-id="<?= $user->id ?>"><?= $user->headquarter->name ?></td>
                <td class="text-center">
                    @can('users.edit')
                        <a href="#" data-click="openForm" data-id="<?= $user->id ?>"><i class="icon-pencil mx-3"></i></a>
                    @endcan
                    @can('users.destroy')
                        <a href="#" data-click="openFormDelete" data-id="<?= $user->id ?>"><i class="icon-close mx-1"></i></a>
                    @endcan
                </td>
            </tr>
        <?php
        endforeach;
        ?>
    </tbody>
</table>
