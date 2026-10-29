<table id="tblList" class="table table-striped table-bordered zero-configuration" >
    <thead>
        <tr>
            <th class="text-center">Nombre</th>
            <th class="text-center">NIT / CC</th>
            <th class="text-center">C. Nacional</th> 
            <th class="text-center">T. Persona</th>
            <th class="text-center">Categoría</th>
            <th class="text-center">Medio de Contacto</th>
            <th class="text-center">Subcontratación</th>
            <th class="text-center">Comercial</th>
            <th class="text-center">Sucursales</th>
            <th class="nofilter text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($crms as $crm)
        <tr>
            <td class="text-center">{{ $crm->full_name}}</td>
            <td class="text-center">{{ $crm->document}}</td>
            
            <td class="text-center"><span class="badge badge-{{$crm->customer_national ? 'info':'dark'}}">{{$crm->customer_national ? 'Si':'No'}}</span></td>
            <td class="text-center">{{ $crm->type_customer ? 'Juridica': 'Natural' }}</td>
            <td class="text-center">{{ $crm->crmCategory ? $crm->crmCategory->name : 'No registra'}}</td>
            <td class="text-center">{{ $crm->meanContact ? $crm->meanContact->name : 'No registra'}}</td>
            <td class="text-center"><span class="badge badge-primary">{{ $crm->customer_subcontract ? 'Si': 'No'  }}</span></td>
            <td class="text-center">
                @if($crm->user)
                    {{ $crm->user->name  }}
                @else
                    <i>Sin asignar</i>
                @endif
            </td>
            <td class="text-center">
                @foreach ($crm->offices as $office)
                    <span class="badge badge-{{ $office->workschedules->count() > 0 ? 'success': 'danger'}}"> {{$office->name}}</span>
                @endforeach
            </td>
            <td>
                <div class="text-center">
                    @if($crm->user_id == Auth::user()->id && $crm->offices->pluck('workschedules')->flatten()->count() == 0)
                        <a href="#" data-click="openFormDelete" data-id="<?= $crm->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                    @else
                        @can('crms.destroy')
                            <a href="#" data-click="openFormDelete" data-id="<?= $crm->id ?>" class="text-danger"><i class="las la-trash la-2x"></i></a>
                        @endcan
                    @endif
                    @can('crms.show')
                        <a href="{{route('crms.show', $crm->id)}}" class="text-success"><i class="las la-folder-open la-2x"></i></i></a>
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
<span align="left" style="margin-top: -48px;">Mostrando del {{$crms->firstItem()}}  al {{$crms->lastItem()}} de {{$crms->total()}} resultados</span>
<div align="right" style="margin-top: -30px;">
    {!!$crms->render()!!}
</div>
