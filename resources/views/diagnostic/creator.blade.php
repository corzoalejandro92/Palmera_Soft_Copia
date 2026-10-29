@php
$title=isset($quote->consecutive) ? "Cotización #  $quote->consecutive  > Creador de diagnósticos" : "Diagnostico #  $diagnostic->id  > Creador de diagnósticos";
@endphp
@extends('layouts.main')
@section('title', $title)

@section('viewjs', 'diagnostic/creator')
@section('viewcss','diagnostic/creator')

@section('select2','true')
@section('lightbox2','true')
@section('sweetAlert','true')
@section('ckeditor', 'true')
@section('wPaintPainterro', 'true')


@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <form action="#" id="form">
                    {{ method_field( isset($diagnostic->id)?'PUT':'POST') }}
                    {{ csrf_field() }}
                    <input type="hidden" value="<?= $diagnostic->id ?? ''; ?>" name="id" />
                    <div class="row">
                       <div class="col-md-12">
                           
                        <span class="last-updated">Ultima actualización: {{$diagnostic->updated_at}}</span>
                       </div>
                    </div>
                   
                    <!-- Intruducción/Header del diagnóstico -->
                    @include('diagnostic.frames.introduction')

                    <!-- Elaboración de plano borrador del diagnóstico  -->
                    @include('diagnostic.frames.drawPlan')

                    <!-- Tabla 1 Plagas reales  -->
                    @include('diagnostic.frames.table1')

                    <!-- Tabla 2 riesgo de plagas futuras  -->
                    @include('diagnostic.frames.table2')

                    <!-- Tabla 3 métodos de control  -->
                    @include('diagnostic.frames.table3')

                    <!-- Tabla 4 métodos de control  -->
                    @include('diagnostic.frames.table4')

                    <!-- Tabla 5 identificacion de plagas y su grado de infestación  -->
                    @include('diagnostic.frames.table5')

                    <!-- Notas -->
                    @include('diagnostic.frames.notes')
                    <hr>
                    <div class="row">
                        
                        <div class="col-md-12">
                            <a  href="#" data-click="save" class="btn btn-success float-right ml-1">Guardar</a>
                            <a  href="#" data-click="cancel"  class="btn btn-outline-dark float-right ml-1">Cancelar</a>
                            
                            <div style="width: 300px!important" class="float-right ml-1">
                            <select class="form-control" name="status_id" style="width: 300px;!important"> 
                               <option value="">Seleccione una opción</option>
                               @if($diagnostic->status)
                                    <option value="{{$diagnostic->status->id}}" selected>{{$diagnostic->status->name}}</option>
                               @endif
                           </select></div>
                           <p for="status_id" class="float-right" style="line-height: 40px;font-weight: 900;">Estado:</p>
                        </div>
                    </div>
                </form>
            </div>
            </div>
    </section>

@endsection
