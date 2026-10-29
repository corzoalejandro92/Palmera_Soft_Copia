<table class="table table-striped table-bordered zero-configuration" id="tblList" >
    <thead>
        <tr>
            <th>Nombre</th>
            <th class="text-center">Ciudad</th>
            <th class="text-center">Dirección</th>
            <th class="text-center">Zonas de cobertura</th>
            <th class="text-center">Teléfono</th>
            <th class="text-center" >Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($headquarters as $headquarter): ?>
            <tr class="pointer">
                <td data-click="openForm" data-id="<?= $headquarter->id ?>" >
                    {{$headquarter->name}}
                </td>
                <td class="text-center" data-click="openForm" data-id="<?= $headquarter->id ?>">{{$headquarter->cities->name}} ({{$headquarter->cities->states->name}})</td>
                <td class="text-center" data-click="openForm" data-id="<?= $headquarter->id ?>">{{$headquarter->address}}</td>
                <td class="text-center" >
                   @foreach($headquarter->zones as $zone)
                     <span class="badge badge-info cursor-pointer m-3px"  data-click="openFormZone"  data-city="<?= $headquarter->name ?>" data-headquarter="<?= $headquarter->id ?>" data-id="{{$zone->id }}">{{$zone->name}}</span>    
                     <i class="las la-times-circle badge-close cursor-pointer" data-click="openFormDeleteZone" data-id="{{$zone->id }}"> </i>
                     @endforeach
                   
                </td>
                <td class="text-center" data-click="openForm" data-id="<?= $headquarter->id ?>"><?= $headquarter->phone ?></td>
                <td class="text-center" style="display: flex;">
                        @can('headquarters.edit')
                            <a href="#" data-click="openFormZone" data-headquarter="<?= $headquarter->id ?>" data-city="<?= $headquarter->name ?>">
                                <i class="las la-map-marked-alt la-2x"></i>
                            </a>
                        @endcan
                        @can('headquarters.show')
                            <a href="#" data-click="openForm" data-id="<?= $headquarter->id ?>">
                                <i class="las la-pencil-alt la-2x"></i>
                            </a>
                        @endcan
                        @can('headquarters.destroy')
                            <i class="las la-trash la-2x" data-click="openFormDelete" data-id="<?= $headquarter->id ?>"></i>
                        @endcan
                </td>
            </tr>
            <?php
        endforeach;
        ?>
    </tbody>
</table>
