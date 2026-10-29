<table class="table table-striped table-bordered zero-configuration" id="tblList" >
    <thead>
    <tr>
        <th class="text-center">Nro.</th>
        <th class="text-center w25p">Nombre</th>
        <th class="text-center">Sedes Palmera</th>
        <th class="text-center w25p">Creado por</th>
        <th class="text-center">Fecha</th>
        <th class="nofilter">Opciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($templates as $template): ?>
        <tr class="pointer">
            <td class="text-center"><?= $template->id ?></td>
            <td class="td-avatars">
                &nbsp;<?= $template->name  ?>
            </td>
            <td>
                @foreach ($template->headquarters as $headquarter)
                    <span class="badge badge-primary"> {{$headquarter->name}}</span>
                @endforeach
            </td>
            <td>
                {{$template->user->name ?? ''}}
            </td>
            <td nowrap>
                {{ucwords($template->created_at->format('d M, Y'))}}
            </td>
            <td class="text-center">
                @can('quotes.config')
                    <a href="#" data-click="openFormDelete" data-id="<?= $template->id ?>"><i class="icon-trash mx-1 text-danger"></i></a>
                @endcan
                @can('quotes.config')
                    <a href="#" data-click="openConfigForm" data-id="<?= $template->id ?>"><i class="icon-pencil mx-3"></i></a>
                @endcan
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
