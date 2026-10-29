<?php use Carbon\Carbon; ?>
 <!-- BEGIN: Content-->
    <div class="content-body">
        <section id="timeline" class="timeline-left timeline-wrapper">
            <ul class="timeline">
                <li class="timeline-line"></li>
                
            </ul>
            <ul class="timeline">
                <li class="timeline-line"></li>
                @forelse($crmTrackings as $date => $group)
                <li class="timeline-group">
                    <a href="#" class="btn btn-primary round">
                        <i class="ft-calendar"></i> {{ $date }}</a>
                </li>
                    @foreach($group as $crmTracking)
  
                        <li class="timeline-item">
                            <div class="timeline-badge">
                                <span class="bg-danger bg-glow bg-darken-1" style='{{ $crmTracking->system ? 'background-color: #ffffff !important;box-shadow: 0px 1px 20px 1px #f95a6278;' : '' }}'>
                                    @if($crmTracking->system)
                                        <i class="las la-robot" style='{{ $crmTracking->system ? 'color: #f95a62;' : '' }}'></i>
                                    @else
                                        <i class="lar la-user"></i>
                                    @endif
                                </span>
                            </div>
                            <div class="timeline-card card border-grey border-lighten-2" style="border: solid;">
                                <div class="card-header">
                                     <h4 class="card-title">
                                        {{$crmTracking->title}}
                                    </h4>
                                    <h4 class="card-title"><br>
                                        <a href="#"><span style="color: #737787;"></span><i class="las la-exclamation-circle"></i>&nbsp; Seguimiento de {{ $crmTracking->crmTrackingType->name}}</a> &dot; {{ Carbon::parse($crmTracking->date)->format('h:i a') }}
                                    </h4>
                                    @if(Auth::user()->id == $crmTracking->user_id && $crmTracking->system == 0)
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li>
                                                    <a>
                                                        <i class="icon-pencil" data-click="openEditEvent" data-id="<?= $crmTracking->id ?>" data-toggle="tooltip" data-placement="right" title="Editar"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div class="col-12">
                                        <p class="card-subtitle text-muted mb-0">
                                            <span class="font-medium-2"><span style="color: #35977a;">Detalle:</span>&nbsp;{{$crmTracking->detail}}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="media-body text-right">
                                    <ul class="list-unstyled users-list">
                                        <li>
                                            <div style="position: relative;right: 40px;">
                                                <img class="avatar avatar-sm pull-up" src="<?= url($crmTracking->commercial->avatar ?? '')?>" onerror="this.src='{{url("images/default/user.jpg")}}'" />
                                                &nbsp;<?= $crmTracking->commercial->name ?? '' ?><br>
                                                &nbsp;<?= $crmTracking->commercial ? ($crmTracking->commercial->roles->implode('name',',') ?? '') : '' ?>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @empty
                <li class="timeline-item">
                    <div class="timeline-badge">
                        <span class="bg-dark bg-glow bg-darken-1">
                            <i class="las la-frown"></i>
                        </span>
                    </div>
                        <i style="position: relative;top: 18px;">No se encontraron eventos registrados para este cliente.</i>
                 </li>
                @endforelse
            </ul>
        </section>
    </div>
    <!-- END: Content-->