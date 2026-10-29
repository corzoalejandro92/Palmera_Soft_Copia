@php
$title=isset($quote->consecutive) ? "Cotización # $quote->consecutive > Creador de diagnósticos" : "Diagnostico # $diagnostic->id > Creador de diagnósticos"
@endphp
@extends('layouts.main')
@section('title', $title)

@section('viewjs', 'diagnostic/creator')
@section('viewcss','diagnostic/creator')

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
        OPEN_IMAGE = '{{isset($plan->path) && $plan->path  ? "/public/storage/".$plan->path : "/images/default/base_plano_diagnostico_f.png"}}';
      
    
    </script>
        {{ method_field( $mode == 'edit' ? 'PUT' : 'POST') }}
        {{ csrf_field() }}
        <input type="hidden" id="diagnostic" value="{{$diagnostic->id?? ''}}" />
        @if(isset($quote->id))
            <input type="hidden" id="quote" value="{{$quote->id}}" />
        @endif
        <input type="hidden" id="plan" value="{{$plan->id ?? ''}}" />
        <div id="conatiner" style="width: 100%!important;height: 90%!important;">

        </div>
@endsection