<table class="table table-striped table-bordered zero-configuration text-inputs-searching" style="width:100%!important;" id="list" >
    <thead>
        <tr>
            <th class="text-center" style="width:50px;">Nro.</th>
            <th class="text-center nofilter" style="width:80px;">Fecha</th>
            <th class="text-center nofilter">Valor</th>
            <th class="text-center">Estado</th>
            <th class="nofilter">Aprobar</th>
            <th class="text-center">Servicios</th>
            <th class="text-center" style="width:50px;">MIP</th>
            <th class="text-center">Archivo</th>
            @can('crms.info.admin')
                <th class="nofilter">Opciones</th>
            @endcan
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th class="text-center" style="width:50px;">Nro.</th>
            <th class="text-center nofilter" style="width:80px;">Fecha</th>
            <th class="text-center nofilter" style="width:80px;">Valor</th>
            <th class="text-center">Estado</th>
            <th class="nofilter">Aprobar</th>
            <th class="text-center">Servicios</th>
            <th class="text-center" style="width:50px;">MIP</th>
            <th class="text-center">Archivo</th>
            @can('crms.info.admin')
                <th class="nofilter">Opciones</th>
            @endcan
        </tr>
    </tfoot>
    <tbody>
        <?php foreach ($quotes as $quote): ?>
            <tr class="pointer">
                <td class="text-center" style="width:80px;"><?= $quote->code ?></td>
                 <td class="text-center">
                    <span class="badge badge-dark">Creada: {{$quote->created_at->format('d/m/Y')}}</span><br>
                    @if (isset($quote->approved_at))
                        <span style="font-size: 12px;" class="badge badge-success">Aprobado: {{ $quote->date_approved_at->format('d/m/Y H:i')}}</span><br>
                    @endif
                 </td>
                <td class="text-center">
                    <b>$<?= number_format($quote->total,0,'.',',') ?></b>
                    @if($quote->status_id == 2)
                        <br><span class="badge badge-success" style="font-size: 12px;">$<?= number_format($quote->services->where('approved',1)->sum('price') ,0,'.',',') ?> aprobado</span>
                    @endif
                </td>
                <td class="text-center">
                    {{ $quote->status->name }}
                </td>
                <td class="text-center">
                    @if($quote->status->id == 1)
                        <fieldset>
                            <input type="checkbox" data-click="approvedStatusForm" data-quote="{{ $quote->id }}" id="input-checkbox-{{ $quote->id }}">
                            <label for="input-checkbox-{{ $quote->id }}">Aprobar</label>
                        </fieldset>
                    @endif
                </td>
                <td style="width:50px;">
                    @foreach ($quote->services as $service)
                    <span class="badge badge-{{$service->approved ? 'success': 'info'}} text-bold-700" style="width: 200px;white-space: inherit;">{{$service->service->name}}
                      
                        @if($service->public_observation)
                        <span>
                            <br><br><b>OBSERVACIONES:</b> {{$service->public_observation}}
                        </span>
                        
                        @endif
                    </span><br><br>
                @endforeach
                </td>
                @if($quote->diagnostic)
                <td style="width:50px;" class="text-center">
                    <span class="badge badge-success text-bold-700"> CLIENTE MIP</span>
                </td>
            </td>
            @else
            <td style="width:50px;" class="text-center">
                <span class="badge badge-warning text-bold-700"> NO MIP</span>
            </td>
            @endif
                <td style="width:50px;" class="text-center">
                    @if($quote->file)
                         <li><i class="las la-paperclip la-2x text-info"></i> <span class="text-info"><a href="{{ route('quote.showfile',["id" => $quote->file->id])}}" target="_blank"><span style="position: relative;top: -5px;">Ver Archivo</span></a></span></li>
                        
                         @endif
                </td>
               
                @can('crms.info.admin')
                    <td class="text-center">
                        <div class="text-center">
                            <a  href="{{route('crm.editQuote',[$quote->id])}}"><i class="icon-pencil mx-3"></i></a>
                        </div>
                    </td>
                @endcan
            </tr>
        <?php endforeach;?>
    </tbody>
</table>