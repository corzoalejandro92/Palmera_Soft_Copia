@extends('layouts.main')

@section('title', 'Importar Data')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('fileInput','true')
@section('viewjs','importFoxcon/index')
@section('viewcss','importFoxcon/index')


@section('content')

<section id="icon-tabs">
    <div class="row">
        <div class="col-12">
            <div class="row card-title">
                <div class="col-md-6">
                    <h4 class="card-title top-5">Importar Data Foxcon</h4>
                </div>
            </div>

            @if(!\App\Integration::find(1)->enabled && 
in_array(
	\App\Headquarter::find(
		session('headquarter_id')
	)->id , \App\Integration::find(1)->headquarters
))
                <div class="alert alert-warning">La integracióon con Foxcon se encuentra deshabilitada</div>
            @endif

            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <section id="configuration">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card pb-2">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <form id="documentDiagnosis" action="{{route('importFoxcon.upload')}}" enctype="multipart/form-data" method="POST">
                                                    {{ method_field('POST') }}
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="headquarter_id" value="{{ session('headquarter_id') }}" >
                                                    
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
                                                            <ul style="color:#000;">
                                                                <li>&mdash; Los productos deben estar homologados corretamente con su código Foxcon.</li>
                                                                <li>&mdash; Las sedes deben tener en su configuración el número de NIT correspondiente a Foxcon.</li>
                                                                <li>&mdash; Las bodegas deben tener los tipos de documentos foxcon configurados con nomenclatura y consecutico (Ejemplo: documento de traslado - TRA0001).</li>
                                                                <li>&mdash; Todas la bodegas (icluyendo técnicos y clientes inhauses) deben tener su código Foxcon correspondiente.</li>
                                                                <li>&mdash; Todos los usuarios (incluyendo técnicos y clientes) que interactuen con el inventario deben tener el id de usuario de Foxcon guardado en su configuración de usuarios</li>
                                                                <li>&mdash; Si no especificas Lote, RSA, o fecha de vencimiento, estos datos serán guardados como 'N/A' respectivamente</li>
                                                               
                                                            </ul>
                                                            <hr>
                                                         </div>
                                                        @if(Auth::user()->roles->pluck('slug')->contains('root') || Auth::user()->roles->pluck('slug')->contains('boss_purchasing'))
                                                        {{-- <div class="col-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" value="1" id="flexCheckChecked" name="truncate" >
                                                                <label class="form-check-label" for="flexCheckChecked">
                                                                  Formatear tablas de inventario para comenzar desde cero (Esto borrará todo el histórico de inventario a la fecha, usálo solo en la primera importación)
                                                                </label>
                                                            </div>
                                                        </div> --}}
                                                        {{-- <div class="col-12"><hr></div>
                                                        <div class="col-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked2" name="approved_loans" >
                                                                <label class="form-check-label" for="flexCheckChecked2">
                                                                  Permitir que el sistema realice la homologación de mecanismos instalados en la bódega de prestamos una vez finalice la importación de saldos (usálo solo en la primera importación)
                                                                </label>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-12"><hr></div>
                                                        <div class="col-12">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="2" id="flexCheckChecked2" name="truncate" >
                                                                <label class="form-check-label" for="flexCheckChecked2">
                                                                  Solo limpiar los datos de esta sede ({{ session('headquarter_name') }})
                                                                </label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-12"  align="right"> 
                                                            <button class="btn btn-success" type="submit">Comenzar</button>
                                                        </div> 
                                                        @endif
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