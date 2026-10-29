<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th style="width: 40%;" class="text-center" style="wo">Nombre</th>
            <th class="text-center">Descripción</th>
            <th class="text-center nofilter">Tipos de plagas</th>
            <th class="text-center nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($infestations as $infestation): ?>
        
        <tr class="pointer">
            <td class="td-avatars"  style="width: 40%;" data-click="openForm" data-id="<?= $infestation->id ?>">
                <div class="row">
                    <div class="col-2">
                        <img class="avatar xxs " src="<?= url($infestation->full_avatar ?? '')?>" onerror="this.src='{{url("images/default/infestation.png")}}'" />
                    </div>
                    <div class="col-10">
                        &nbsp;<?= $infestation->name  ?>
                    </div>
                </div>
            </td>
            
            <td style="width: 55%;" class="text-center">{{$infestation->description ?? 'Vacio'}}</td>
            <td style="width: 55%;" class="text-center">
                @foreach ($infestation->plagas as $plaga)
                    <span class="badge badge-primary">{{$plaga->name}}</span>
                @endforeach
            </td>
            <td class="option-button">
                <div class="text-center">
                    @canAny(['infestations.show','infestations.edit'])
                        <a href="#"><i class="icon-eye mx-3 " data-click="openForm"
                            data-id="<?= $infestation->id ?>"></i></a>
                    @endcanAny
                    @canAny(['infestations.destroy'])
                        <a href="#"><i class="icon-close mx-1 " data-click="openFormDelete"
                            data-id="<?= $infestation->id ?>"></i></a>
                    @endcanAny
                </div>
            </td>
        </tr>
        <?php 
        endforeach;
        ?>
    </tbody>
</table>