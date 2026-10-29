@php 
$binary = base64_decode($file->base64);
$imageProp = getimagesizefromstring($binary);

@endphp
@extends('layouts.main')
@section('title', $crm->name. ' > Area '. $area->name . ' > Plano '.$file->fileType->name .' > Mecanismos')


@section('viewjs', 'crm/common')
@section('viewjs2', 'crm/'.($type== "Mecanismos" ? 'mechanism': 'risks'))

@section('viewcss','crm/mechanism')

@section('select2','true')
@section('sweetAlert','true')
@section('jqueryUI','true')
<style>
    html body .content .content-wrapper {
        padding: 0rem!important;
    }
</style>

 
<script>
    localStorage.setItem('menuLocked',false);
    localStorage.setItem('defaultZoom',false);
    //document.body.style.zoom = "100%";

</script>

@section('content')
   
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <input type="hidden" id="crm" value="{{$crm->id}}" />
        <input type="hidden" id="office" value="{{$office->id}}" />
        <input type="hidden" id="area" value="{{$area->id}}" />
        <input type="hidden" id="file" value="{{$file->id}}" />
    
         <section id="icon-tabs">
            <div class="row" style="padding: 35px;">
                <div class="col-lg-3 col-md-4" >

                    <div class="card"  style="height: 100px;">
                        <div class="card-body">
                            <h4>Tamaño del mecanismo <i class="la la-undo-alt la-1x restore-size" onclick="restoreDefaultSize();"></i></h4>
                            <div class="slidecontainer">
                                <input type="range" min="1" max="100" value="30" class="slider" id="customRange">
                                <p style="margin-top: 10px;"><span id="demo"></span> pixeles</p>
                            </div>
                        </div>
                    </div>

                    <div class="card"  style="height: 500px;overflow-y: scroll;">
                        <div class="card-body">
                            <h4>{{$type == 'Mecanismos' ? 'Tabla de Conveciones' : $type}}</h4>
                                <ul class="list-mechanism">
                                    @foreach($elements as $element)
                                    <li class="">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div  style="background-color:{{ $element->color }};"
              
                                                    data-element=  "{{$element->id}}"
                                                    data-plan= "{{$file->id}}"
                                                    data-type= "{{$element->id}}"
                                                    class="avatar-shadow mechanism convention-md-dot" 
                                                    ></div>
                                               
                                            </div>
                                            <div class="col-md-9">
                                                <span class="avatar-span"><b>{{$element->slug}}:</b> {{$element->name}}</span>
                                            </div>
                                        </div>
                                    </li>
                                    @endForeach
                                </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8" style="background: #303339;padding: 0px; border-radius: 5px;" id="cardImage">
                    <div class="card text-center ">
                        <div class="card-body" style="padding: 0px!important;">
                        <div id="imageCanvas" class="plan" style="background-image: url({{'data:image/jpeg;base64,' .$file->base64}}); height: {{$imageProp[1]}}px; width:{{$imageProp[0]}}px; position: relative;" >
                        @foreach ($file->extends as $extend)
                            @php
                                    $xmargin = $extend->size /10;
                                    $ymargin = $extend->size /40;
                                    $title= $type == 'Mecanismos' ? $extend->extendible->name : $extend->extendible->infestation->plagas->where('id',$extend->extendible->id)->first()->description;
                            @endphp
                              <div  
                                data-plan= "{{$file->id}}" 
                                data-id="{{$extend->id}}" 
                                data-y="{{$extend->y}}" 
                                data-num="{{$extend->num}}" 
                                data-x="{{$extend->x}}" 
                                data-size="{{$extend->size}}" 
                                data-type="{{$extend->extendible->id}}" 
                                data-title="{{$type}}" 
                                data-slug={{$extend->extendible->slug}}
                                class="avatar-shadow mechanismP delete{{$extend->id}} ui-draggable ui-draggable-handle mCS_img_loaded convention-md-dot" 
                                title="{{$title}}" 
                                style="position: absolute; top: {{$extend->y}}%; left: {{$extend->x}}%; height: {{$extend->size}}px!important; width:  {{$extend->size}}px!important; background-color:{{ $extend->extendible->color }};"
                                ></div>
                            <i class="la la-chevron-circle-down la-2x deleteB{{$extend->id}} remove-mechanism" title="{{$extend->extendible->name}}" data-id="{{$extend->id}}" data-toggle="dropdown" data-title='{{$type}}' data-click='options' style="position: absolute; top: {{$extend->y+$ymargin}}%; left: {{$extend->x + $xmargin}}%; height: {{$extend->size}}px!important; width:  {{$extend->size}}px!important;"></i>
                            @if($type == 'Mecanismos')
                            <span class="spantext deleteT{{$extend->id}}" style="position: absolute; top: {{$extend->y +$ymargin}}%; left: {{$extend->x+$xmargin}}%;width:auto;background-color:transparent;color:#000;border-radius:0px;font-size:0.6rem; padding:1px;font-weight: 900;">
                                {{$extend->extendible->slug}} {{$extend->num}}
                            </span>

                               
                            @endif
                        @endforeach
                            <div class="dropdown" style="z-index:100000!important;">
                                <ul class="dropdown-menu" role="menu" >
                                    <div id="options">
                                    </div>
                                </ul>
                            </div>
                    </div>
                        </div>
                    </div>
                </div>
                @if($type == 'Plagas')
                    @php
                        $unique = $file->extends->unique('extendible_id');
                    @endphp
                    <div class="col-lg-3" style="background-color:#fff"></div>
                    <div class="col-lg-9" style="background-color:#fff">
                        <br>
                        <div class="row">
                            @foreach($unique as $uniq)
                                <div class="col-3 mb-2" >
                                    <img 
                                        src="{{$uniq->extendible->full_avatar}}" 
                                        class="avatar-shadow" 
                                        title="{{$uniq->extendible->slug}}" 
                                        >
                                    <span ><b>{{$uniq->extendible->name}}</b></span>
                                </div>
                            @endForeach
                        </div>
                    </div>
                @endif
            </div>
        </section>  
@include('crm.sub.mdl_delete')
@include('crm.sub.mdl_formShow')

@endsection