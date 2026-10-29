@extends('layouts.error')
@section('title', 'Imprimiendo...')
@section('viewcss','errors/error')
@section('content')

<div class="text" style="font-size: 15em!important;">Imprimiendo...</div>

<div class="div-message" style="top: 448px;position: relative;">
    <h4>Se ha enviado una solicitud de impresión a {{$ip}}:{{$port}}</h4>
    <h4>Puedes cerrar esta pestaña cuando el proceso finalice.</h4>
</div>
</div>
@endsection