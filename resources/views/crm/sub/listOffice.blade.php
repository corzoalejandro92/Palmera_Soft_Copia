<table class="table text-inputs-searching table-bordered zero-configuration" style="width:100%!important;" id="list">
    <thead>
        <tr>
            <th>Cuidad</th>
            <th>Sede</th>
            <th>dirección</th>
            @can('crms.info.admin')
                <th class="nofilter"> Opciones</th>
            @endcan
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Cuidad</th>
            <th>Sede</th>
            <th>dirección</th>
            @can('crms.info.admin')
                <th class="nofilter"> Opciones</th>
            @endcan
        </tr>
    </tfoot>
    <tbody>
        
        @foreach ($offices as $office)
        <tr>
            <th scope="row">{{ $office->cities->name ?? ''}}</th>
            <td>{{$office->name}}</td>
            <td>{{$office->address}} <i style="color: #b5b3b3;">{{$office->observation}}</i></td>
            @can('crms.info.admin')
                <td>
                    <div class="text-center">
                        <a href="#" data-click="openEditOffice" data-id="<?= $office->id ?>"><i class="icon-pencil mx-3"></i></a>
                        <a href="#" data-click="openFormDeleteOffice" data-id="<?= $office->id ?>"><i class="icon-close mx-1"></i></a>
                    </div>
                </td>
            @endcan
        </tr>
        @endforeach
        
    </tbody>
</table>