

@extends('layouts.main')
@section('title', 'Control de integraciones')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('select2','true')
@section('icheck','true')
@section('dynamic-form', 'true')
@section('fileInput', 'true')
@section('viewjs', 'home/index')
@section('viewcss','crm/show')
@section('toastr', 'true')
@section('map','true')

@section('content')


<section id="bootstrap-switch">
    <div class="row justify-content-md-center">
        <div class="col-6 mt-3 mb-1 ">
            <h4 class="text-uppercase">Integraciones API</h4>
            <small class="">Por medio de estos controles podrás habilitar/Deshabilitar las integraciones del soft.</small>
        </div>
    </div>

    @foreach ($integrations as $integration)

    <div class="row match-height justify-content-md-center">

        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <img src="{{ $integration->base64_logo ?? 'images/default/default.jpg' }} " style="width:45px; margin-right: 10px;">
                    <div style="display: inline-grid;">
                        <h4 class="card-title"  style="display:block;">{{ $integration->name }}</h4>
                        <div class="custom-control custom-switch" style="transform: scale(1.1);transform-origin: left;  display: inline-flex;margin-left: 10px;">
                            <input type="checkbox" class="custom-control-input" data-id="{{ $integration->id }}" name="integration-switch" id="customSwitch{{ $integration->id }}" {{ $integration->is_backend ? 'disabled': '' }} {{ $integration->enabled ? 'checked':'' }}>
                            <label class="custom-control-label" for="customSwitch{{ $integration->id }}">{{ $integration->description ?? 'Habilitada/Deshabilitada'}}</label>
                        </div>
                    </div>

                    <div class="custom-control custom-switch" style="transform: scale(1.1);transform-origin: left;margin-left: 100px; padding-top:20px; padding-bottom:20px;">
                        @if($integration->id == 1)

                        <div class="row">
                            <div class="col-12" style="margin-top:10px;">
                                <input type="checkbox" class="custom-control-input" data-id="{{ $integration->id }}" data-headquarter="99999999" name="integration-switch-headquarters[]" id="customSwitch-headquarter99999999"  {{ $integration->headquarters && in_array('99999999', $integration->headquarters) ? 'checked':'' }}>
                                <label class="custom-control-label" for="customSwitch-headquarter99999999">Bodega Nacional</label>
                            </div>
                        </div>

                        @foreach (\App\Headquarter::all() as  $headquarter)
                            <div class="row">
                                <div class="col-12" style="margin-top:10px;">
                                    <input type="checkbox" class="custom-control-input" data-id="{{ $integration->id }}" data-headquarter={{ $headquarter->id }} name="integration-switch-headquarters[]" id="customSwitch-headquarter{{ $headquarter->id }}"  {{ $integration->headquarters && in_array($headquarter->id, $integration->headquarters) ? 'checked':'' }}>
                                    <label class="custom-control-label" for="customSwitch-headquarter{{ $headquarter->id }}">{{ $headquarter->name ?? 'Habilitada/Deshabilitada'}}</label>
                                </div>
                            </div>
                        @endforeach
                        
                       
                        @endif
                    </div>
                    
                   

                    

                    
                    
                </div>
               
            </div>
        </div>
        
        
    </div>

    @endforeach
</section>

@endsection