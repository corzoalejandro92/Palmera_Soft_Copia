<div class="col-xl-4 col-lg-6 col-12">
    <div class="card bg-gradient-directional-warning" style="background-image: -webkit-linear-gradient(45deg, #607d8b, #000000d1),url({{$file->url}}); background-size: cover;">
        <div class="card-content">
            <div class="card-body">
                <div class="media d-flex">
                    <div class="media-body text-white text-left align-self-bottom mt-3">
                        <span class="d-block mb-1 font-medium-1">Plano</span>
                        <h3 class="text-white mb-0">{{$file->fileType->name}}</h3>
                    </div>
                    <div class="align-self-top">
                        
                        <div class="dropdown">
                            <a  data-toggle="dropdown" href="#" title="ver opciones">
                                <i class="las la-pencil-ruler icon-opacity text-white font-large-4 float-right"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                @if(in_array($file->fileType->slug,['plan-mec','plan-risk']))
                                    <li><a href="{{route('crm.previewfile',['id'=>$file->id])}}" target="_blank"><i class="las la-eye la-2x"></i><span>Abrir</span></a></li>
                                @elseif(in_array($file->fileType->slug,['plan-route']))
                                <li><a href="{{route('crm.showPlanRoute',['id'=>$file->id])}}" target="_blank"><i class="las la-eye la-2x"></i><span>Abrir</span></a></li>
                                @else
                                    <li><a href="{{route('crm.showfile',['id'=>$file->id])}}" target="_blank"><i class="las la-eye la-2x"></i><span>Abrir</span></a></li>
                                @endif
                                @can('crms.info.admin')
                                    <li><a href="{{route('crm.editPlan',
                                        [
                                            'area'=> $area->id, 
                                            'crm' => $crm, 
                                            'plan'=> $file->id,
                                            'office' => $office
                                        ])}}" target="_blank"><i class="las la-edit la-2x"></i><span>Editar</span>
                                        </a>
                                    </li>
                                    @if($file->fileType->slug == 'plan-mec')
                                        <li><a href="{{route('crm.drawMec',
                                            [
                                                'area'=> $area->id, 
                                                'crm' => $crm, 
                                                'plan'=> $file->id,
                                                'office' => $office
                                            ])}}" target="_blank"><i class="las la-crosshairs la-2x"></i><span>Ubicar mecanismos</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if($file->fileType->slug == 'plan-mec')
                                        <li><a href="{{route('crm.listMec',
                                            [
                                                'area'=> $area->id, 
                                                'crm' => $crm, 
                                                'plan'=> $file->id,
                                                'office' => $office
                                            ])}}" target="_blank"><i class="las la-list-ul la-2x"></i><span>Listar mecanismos</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if($file->fileType->slug == 'plan-risk')
                                        <li><a href="{{route('crm.drawRisk',
                                            [
                                                'area'=> $area->id, 
                                                'crm' => $crm, 
                                                'plan'=> $file->id,
                                                'office' => $office
                                            ])}}" target="_blank"><i class="las la-radiation la-2x"></i><span>Ubicar riesgos</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="separator"><hr></li>
                                    <li class="text-danger" data-click="removePlanRow" data-id="{{$file->id}}"><a><i class="las la-trash-alt la-2x"></i><span>Eliminar</span></a></li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
