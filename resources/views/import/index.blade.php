@extends('layouts.main')

@section('title', 'Importar Data')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('fileInput','true')
@section('select2','true')
@section('viewjs','import/index')
@section('viewcss','import/index')


@section('content')

<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Importar Data</h4>
                </div>
            </div>

            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <section id="configuration">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card pb-2">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <form id="documentDiagnosis" action="{{route('import.upload')}}" enctype="multipart/form-data" method="POST">
                                                    {{ method_field('POST') }}
                                                    {{ csrf_field() }}
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="dropzone dropzone-area">
                                                                <div class="col-12">
                                                                    <div class="content-diagnosis">
                                                                        <div data-upload="file" class="pointer text-center">
                                                                            <img class="avatar md pointer"src="<?= asset('images/default/document.png'); ?>" />
                                                                            <div class="mt-1">
                                                                                <div class="custom-file">
                                                                                    <div class="custom-file">
                                                                                        <input type="file" name="file" class="custom-file-input" id="inputGroupFile01" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                                                                        <label class="custom-file-label" for="inputGroupFile01">Seleccione un archivo</label>
                                                                                    </div>
                                                                                </div>
                                                                                <br><br>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <h5>Hojas que se desean importar</h5>
                                                                                            <select class="form-control select2" name="sheet_to_import[]" data-change="hiden-error" multiple>
                                                                                           <optgroup label="Modulo de Clientes">
                                                                                                <option value="CRM">CRM</option>
                                                                                                <option value="CRM Sucursales">CRM Sucursales</option>
                                                                                                <option value="CRM Contactos">CRM Contactos</option>
                                                                                           </optgroup>
                                                                                           <optgroup label="Modulo de Servicios">
                                                                                            <option value="Servicios">Servicios</option>
                                                                                            <option value="Tarifa de servicios x sedes">Tarifa de servicios x sedes</option>
                                                                                            <option value="Parametros adicionales de servicios x sedes">Parametros adicionales de servicios x sedes</option>
                                                                                       </optgroup>
                                                                                            
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-12">
                                                           &nbsp;
                                                         </div>
                                                        <div class="col-12">
                                                            <h4><b>Antes de comenzar a importar datos, debes tener en cuenta lo siguiente:</b></h4>
                                                            <ul>
                                                                <li>&mdash; Las pestañas marcadas con color rojo y naranja dentro del formato de importación son obligatorios.</li>
                                                                <li>&mdash; Las columnas con títulos señalados con un astetrísco (*) son obligatorios.</li>
                                                                <li>&mdash; Las columnas que añadas al formato serán ingnoradas en el proceso, procura mantener la estructura original.</li>
                                                                <li>&mdash; Los campos relacionales  con formato [#-texto] deben coincidir en las otras pestañas del archivo.</li>
                                                                <li>&mdash; Algunas celdas del archivo de importación deberán ser validadas por medio de un formulario de homologación.</li>
                                                                <li>&mdash; En algunos casos el formato puede sufrir daños o puede ser rechazado por su tipo de extensión, asegúrate de guardar siempre en formato <b>.xlsx</b></b>.</li>
                                                            </ul>
                                                         </div>
                                                         <div class="col-12"  align="right"> 
                                                            <button class="btn btn-success" type="submit">Continuar</button>
                                                        </div> 
                                                    </div>
                                                   
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<!--- Incluir modales del módulo -->
@include('infestation.sub.mdl_form')
@include('infestation.sub.mdl_delete')
@endsection