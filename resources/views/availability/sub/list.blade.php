<table class="table table-striped table-bordered zero-configuration" id="tblList" >
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Dias Laborales</th>
            <th>Jornadas</th>
            <th>Técnicos</th>
            <th>Disponible festivos</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($availabilities as $availability): ?>
            <tr class="pointer">
                <td data-click="openForm" data-id="<?= $availability->id ?>" >
                   {{$availability->name}}
                </td>
                <td>
                    {{$availability->businessDays->pluck('name')->implode(', ')}}
                </td>
                <td>
                    @foreach($availability->workingHours as $workingHour) 
                <span class="badge {{$workingHour->extra ? 'badge-danger':'badge-info'}} m-3px">{{$workingHour->start}} - {{$workingHour->end}}</span>
                    @endforeach
                </td>
                <td>
                   @if($availability->users)
                    @foreach ($availability->users as $user)
                        <span class="badge badge-dark">{{$user->name}}</span>
                    @endforeach
                   @endif
                </td>
                
                <td>
                    {{$availability->holidays ? 'No': 'Si'}}
                </td>
                
                <td class="text-center">
                    @can('availability.show')
                       <a href="#" data-click="openForm" data-id="<?= $availability->id ?>"><i class="icon-pencil mx-3"></i></a>
                    @endcan
                    @can('availability.destroy')
                       <a href="#" data-click="openFormDelete" data-id="<?= $availability->id ?>"><i class="icon-close mx-1"></i></a>
                    @endcan
                </td>
            </tr>
            <?php
        endforeach;
        ?>
    </tbody>
</table>
