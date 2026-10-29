<br><br><br><br><h3>Servicios y técnicos fijos</h3>
<hr>

<table class="table table-bordered" style="width:100%!important;" id="list">
    <thead>
        <tr>
            <th>Servicio</th>
            <th>Hora fijada</th>
            @can('crms.info.admin')
            <th>Estado inicial</th>
            @endif
            <th>Cant. técnicos</th>
            <th>Técnico(s)</th>
            <th>Vigencia</th>
            @can('crms.info.admin')
                <th class="nofilter">Opciones</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if(isset($technicals->services))
    <?php foreach ($technicals->services as $technical): ?>
        <tr>
            <td class="text-center">{{$technical->name}}</td>
            <td class="text-center">{{$technical->pivot->time}}</td>
            @can('crms.info.admin')
                <td class="text-center">{{\App\CrmScheduleStatus::find($technical->pivot->init_status)->name }}</td>
            @endif
            <td class="text-center">{{$technical->pivot->technical_quantity}}</td>
            <td class="text-center">{!! $technical->pivot->technicals ? App\User::find($technical->pivot->technicals)->implode('name',', <br>') : '' !!}</td>
            <td class="text-center">{{$technical->pivot->start}} @if($technical->pivot->end) al @endif <br> {{$technical->pivot->end}}</td>
            @can('crms.info.admin')
                <td>
                    <div class="text-center">
                        <a href="#" data-click="openEditTechnical" data-id="<?= $technicals->id ?>"><i class="icon-pencil mx-3"></i></a>
                    </div>
                </td>
            @endif
        </tr>
        <?php endforeach;?>
        @endif
    </tbody>
</table>

<br><br><br><h3>Técnico de la próxima visita programada</h3>
<hr>
<p>Aún no hay información para mostrar aquí.</p>