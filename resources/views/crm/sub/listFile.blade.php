<table class="table text-inputs-searching table-bordered zero-configuration" style="width:100%!important;" id="list">
    <thead>
        <tr>
            <th>Sucursal</th>
            <th>Archivo</th>
            <th>Tipo de archivo</th>
            <th>Observaciones</th>
            @can('crms.info.admin')
            <th class="nofilter all">Opciones</th>
            @endif
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Sucursal</th>
            <th>Archivo</th>
            <th>Tipo de archivo</th>
            <th>Observaciones</th>
            @can('crms.info.admin')
            <th class="nofilter">Opciones</th>
            @endif
        </tr>
    </tfoot>
    <tbody>
    <?php foreach ($crms->files as $crm): ?>
        <tr>
            <td>Sin especificar</td>
            <th><a  href="{{route('crms.showfile',['id'=>$crm->id])}}"  target="_blank"><i class="la la-eye"></i> {{$crm->name ?? 'desconocido'}}</a></th>
            <td>{{$crm->fileType->name}}</td>
            <td>{{$crm->observation}}</td>
            <td>
                <div class="text-center">
                    <a href="#" data-click="openEditFile" data-id="<?= $crms->id ?>"><i class="icon-pencil mx-3"></i></a>
                </div>
            </td>
        </tr>
    <?php endforeach;?>
    <?php foreach ($offices as $office): ?>
        <?php foreach ($office->files as $file): ?>
            <tr>
                <td>{{$office->name .' - '.$office->full_address}}</td>
                <th><a  href="{{route('crms.showfile',['id'=>$file->id])}}"  target="_blank"><i class="la la-eye"></i> {{$file->name ?? 'desconocido'}}</a></th>
                <td>{{$file->fileType->name}}</td>
                <td>{{$file->observation}}</td>
                @can('crms.info.admin')
                <td>
                    <div class="text-center">
                        <a href="#" data-click="openEditFile" data-id="<?= $office->crm_id ?>"><i class="icon-pencil mx-1"></i></a>
                    </div>
                </td>
                @endif
            </tr>
        <?php endforeach;?>
    <?php endforeach;?>
    </tbody>
</table>