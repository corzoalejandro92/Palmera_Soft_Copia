<?php
    use Illuminate\Support\Collection;
?>
<div class="modal-header">
    <h4 class="modal-title">Visualizacion de reportes</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row match-height">
		@forelse ($reports as $idx =>$report)
            <div class="col-xl-12 col-md-12">
                <div class="card" >
                    <div class="card-body" style="background: #efefef; border-radius: 12px!important;">
                        <h4 class="card-title" style="font-size:20px;">{{($idx+1)}}. - {{$report->extend ? $report->extend->extendible->slug : ''}}#{{ $report->extend->num}} {{$report->type->name}}</h4>
                        
              
                        <hr>
                        <h6 class="card-subtitle" style="font-size:12px;"><b>Fecha:</b> {{ \Carbon\Carbon::parse($report->created_at)->translatedFormat('F\ d \d\e Y\, \a \l\a\s h:i a ')}}</h6>
                    
                        <h6 class="card-subtitle" style="font-size:12px;"><b>Ubicación:</b> {{$report->scheduling->office->name}} > Área {{ isset($report->extend->file->filable->name) ? $report->extend->file->filable->name : 'NO APLICA'}} > Plano de mecanismos > {{$report->extend ? $report->extend->extendible->slug : ''}}#{{ $report->extend->num}}</h6>
                        @if($report->type->pest_required)
                        <h6 class="card-subtitle" style="font-size:12px;"><b>Plaga Capturada:</b> {{$report->pest->name ?? ''}}({{$report->quantity ?? ''}})</h6>
                        @endif
                        <hr>

                        <h6 class="card-subtitle" style="font-size:14px;"><b>Observación:</b> {{$report->observation}} </h6>

                        @if($report->file_url )
                        
                            <div id="carousel-area" class="carousel slide" data-ride="carousel" align="center">
                                <ol class="carousel-indicators">
                                    
                                    <li data-target="#carousel-area" data-slide-to="0" class="active"></li>
                            
                                </ol>
                                <div class="carousel-inner" role="listbox">
                                
                                        <div class="active">
                                            <img src="{{$report->file_url}}" class="d-block w-100 rounded"  style="object-fit: cover; border-radius: 12px!important;">
                                        </div>
                                
                                </div>
                            
                            </div>
                        @endif
        
                        <div class="card-footer border-top-blue-grey border-top-lighten-5 text-muted d-flex justify-content-end align-items-center" style="background: #efefef; gap:10px; padding:5px!important;">
                            <span class="float-left"  style="font-size:12px; font-weight: 800;">Reportado por:</span>
                            <span class="tags float-right d-flex justify-content-end align-items-center"  style="font-size:12px; gap:5px;">
                                <img class="avatar xxs" @if(isset($report->user->avatar)) src="{{url($report->user->avatar)}}"  @endif onerror="this.src='{{asset('images/default/user.jpg')}}'">
                                <span style="font-size:12px">@if(isset($report->user->name)){{$report->user->name}} @else {{"Sin tecnico"}} @endif</span>
                            </span>
                        </div>
                        
                    </div>
                  
                </div>
            </div>
        @empty
            <div class="col-xl-12 col-md-12">
                <div class="card" >
                    <h2 class="text-center">Sin reportes</h2>
                </div>
            </div>
        @endforelse
	</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
</div>

