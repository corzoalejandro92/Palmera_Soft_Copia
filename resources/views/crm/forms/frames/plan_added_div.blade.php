<div role="tabpanel" class="tab-pane {{isset($count) &&$count == 0 ? 'active':''}}" id="tabArea{{$area->id ?? ''}}" aria-expanded="true" aria-labelledby="baseIcon-tab1">
    <!--<h4 class="card-title">Subir Planos o crear uno desde cero
        <a class="btn btn-outline-info" href="{{route('crm.drawPlan',
        [
            'area'=>$area->id, 
            'crm' => $crm, 
        ])}}" target="_blank">
                <i class="la la-eyedropper"></i>
        </a>
    </h4> -->
    @can('crms.info.admin')   
    <h4 class="card-title">
        <a class="btn btn-outline-info" href="{{route('crm.drawPlan',
        [
            'area'=>$area->id, 
            'crm' => $crm, 
            'office' => $office
        ])}}" target="_blank">
                <i class="la la-plus"></i>
        </a> &nbsp; Añadir un nuevo plano en el área {{$area->name}}
    </h4>
    @endcan  
        <div class="row">
            @if(isset($area->files))
                @forelse($area->files as $file)
                    @php $key = $file->id; @endphp
                    @include('crm.forms.frames.plan_block_list',compact(['key','file','crm','area','office']))
                    @empty
                    <div class="alert alert-light mb-2 col-12" role="alert" align="center">
                        <strong>No hay planos anexados para este área</strong>
                    </div>
                    @endforelse
            @else
                @php $key = uniqid(); $plan=null; @endphp
                @include('crm.forms.frames.plan_block_list',compact(['key','plan','area','crm','office']))
            @endif
        </div>
</div>