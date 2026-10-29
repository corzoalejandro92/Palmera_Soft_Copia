<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th >Placa</th>
            <th>Tipo de vehículo</th>
            <th>Sede</th>
            <th>Archivos</th>
            <th class="px350">Vehículo asignado a:</th>
            <th>Alertas de vencimientos</th>
            <th class="nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vehicles as $vehicle)

        <?php  

            if($vehicle->files){
                $documentPluck = $vehicle->files->pluck('fileType.name')->flatten()->implode(' - ');
            }else{
                $documentPluck = '';
            }
            
        ?>


        <tr>
            <td>{{$vehicle->plaque}}</td>
            <td>{{$vehicle->vehicleTypes->name ?? 'Vacio'}}</td>
            <td>{{$vehicle->headquarter->name ?? 'Vacio'}}</td>
            <td>{{ !empty($documentPluck)? $documentPluck: '--'}}</td>

            <td><select class="select2 form-control userVehicle"  data-id="{{ $vehicle->id}}" data-change="saveListVehicle">
                    <option value="">-Seleccionar-</option>
                    <?php if (isset($vehicle->users)): ?>
                    <option value="<?= $vehicle->users->id;?>"  selected><?= $vehicle->users->name; ?></option>
                    <?php endif; ?>
                </select>
            </td>
            <td>
                @if($vehicle->date_soat)
                    <span class="badge badge-info"><b>SOAT:</b> {{$vehicle->date_soat}}</span>
                @endif

                @if($vehicle->date_mechanics)
                    <span class="badge badge-info"><b>Técnomecánica:</b>  {{$vehicle->date_mechanics}}</span>
                @endif

                @if($vehicle->date_secure)
                    <span class="badge badge-info"><b>Seguro:</b>  {{$vehicle->date_secure}}</span>
                @endif
            </td>

            <td>
                <div class="text-center">
                    @canAny(['vehicles.show','vehicles.edit'])
                    <a href="#" data-click="openForm" data-id="<?= $vehicle->id ?>" class="text-success"><i class="las la-eye la-2x"></i></a>
                    @endcan
                    @can('vehicles.destroy')
                    <a href="#" data-click="openFormDelete" data-id="<?= $vehicle->id ?>" class="text-danger"><i
                            class="las la-trash la-2x"></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
