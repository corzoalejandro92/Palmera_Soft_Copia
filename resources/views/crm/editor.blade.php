

@extends('layouts.main')
@section('title', $crm->name. ' > Area '. $area->name . ($mode == 'edit'  ? ' > Plano '.$plan->fileType->name : ' > Nuevo Plano'))


@section('viewjs', 'crm/editor')
@section('viewcss','quote/index')

@section('select2','true')
@section('wPaintPainterro','true')
@section('sweetAlert','true')


@section('content')
    <style>
        html body .content .content-wrapper {
            padding: 0rem!important;
        }
    </style>
    <script>
        localStorage.setItem('menuLocked',false);
        localStorage.setItem('defaultZoom',false);
        document.body.style.zoom = "100%";
        OPEN_IMAGE = '{{$plan->url ?? false}}';
        @if($mode == 'edit')
            SET_TYPE = '{{$plan->type_id ?? false}}';
        @endif

    </script>
        {{ method_field( $mode == 'edit' ? 'PUT' : 'POST') }}
        {{ csrf_field() }}
        <input type="hidden" id="plan" value="{{$plan->id?? ''}}" />
        <input type="hidden" id="crm" value="{{$crm->id}}" />
        <input type="hidden" id="area" value="{{$area->id}}" />
        <input type="hidden" id="office" value="{{$office->id}}" />
        <div id="conatiner" style="width: 100%!important;height: 90%!important;">

        </div>
@endsection