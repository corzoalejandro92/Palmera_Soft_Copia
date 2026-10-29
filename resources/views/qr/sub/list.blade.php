<table class="table table-striped table-bordered zero-configuration" id="tblList" >
    <thead>
        <tr>
            <th>#</th>
            <th class="text-center">Lote</th>
            <th class="text-center">Usados</th>
            <th class="text-center">Fecha creación</th>
            <th class="text-center">Sede</th>
            <th class="text-center">Uso</th>
            <th class="text-center" >Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($qrs as $qr): ?>
            <tr class="pointer">
                <td  data-id="<?= $qr->id ?>" >{{$qr->id}}</td>
                <td class="text-center"  data-id="<?= $qr->id ?>">{{$qr->qrs_count}}</td>
                <td class="text-center"  data-id="<?= $qr->id ?>">
                    {{$qr->lot}}
                    @php try{$progress = number_format(($qr->actives_count * 100) / $qr->qrs_count,0);}
                        catch(Exception $ex){$progress = 0; } 
                    @endphp
                    <div class="row" align="center">
                        <div class="progress progress-sm mt-1 mb-0 col-md-4" style="margin-top: 0.6rem !important;padding-right: 0px;padding-left: 0px;">
                            <div 
                                class="progress-bar bg-gradient-x-info" 
                                role="progressbar" 
                                style="width: {{$progress}}%" 
                                aria-valuenow="{{$progress}}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <div class="col-md-8" style="text-align: left;">
                            {{$qr->actives_count}} de {{$qr->qrs_count}}
                        </div>
                    </div>
                </td>
                <td class="text-center"  data-id="<?= $qr->id ?>">{{$qr->created_at}}</td>
                <td class="text-center"  data-id="<?= $qr->id ?>">{{$qr->headquarter->name}}</td>
                @if(optional(optional($qr->qrs)->first())->use > 0)
                    <td class="text-center"  >{{ $qr->qrs->first()->use == 1 ? "Técnico" : "Administrativo" }}</td>
                @else
                    <td class="text-center"  data-id="<?= $qr->id ?>"></td>
                @endif
                <td class="text-center">
                    @if($qr->actives_count == 0)
                        @can('qrs.show')
                            <a href="{{route('qrs.show', ['id'=>$qr->id])}}" target="_blank"  data-id="<?= $qr->id ?>"><i class="las la-print la-2x"></i></i></a>
                        @endcan
                        @can('users.destroy')
                        
                                <a href="#" data-click="openFormDelete" data-id="<?= $qr->id ?>"><i class="icon-close mx-1 text-danger"></i></a>
                        @endcan
                    @else
                        No disponibles
                    @endif
                </td>
            </tr>
        <?php
        endforeach;
        ?>
    </tbody>
</table>
