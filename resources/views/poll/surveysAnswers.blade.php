@extends('layouts.main')
@section('title', 'Lista de respuestas encuestas')
@section('datatableExport', 'true')
@section('datatables', 'true')
@section('sweetAlert','true')
@section('viewjs', 'poll/index')


@section('content')
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="row card-title">
                    <div class="col-md-6">
                        <h4 class="card-title top-5">Respuestas Encuestas</h4>
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
                                                   <div class="row">
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="dataTables_length" id="tblList_length">
                                                                <label style="display: inline-flex;">Mostrar 
                                                                    <select style="position: relative;top: -10px;margin-left: 5px;margin-right: 5px;" name="tblList_length" aria-controls="tblList" class="form-control form-control-sm">
                                                                        <option value="10" {{$answersSurveys->perpage() == 10 ? 'selected' : ''}} >10</option>
                                                                        <option value="25" {{$answersSurveys->perpage() == 25 ? 'selected' : ''}} >25</option>
                                                                        <option value="50" {{$answersSurveys->perpage() == 50 ? 'selected' : ''}} >50</option>
                                                                        <option value="100" {{$answersSurveys->perpage() == 100 ? 'selected' : ''}} >100</option>
                                                                    </select> 
                                                                    registros
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6" align="right">
                                                                <div id="tblList_filter" class="dataTables_filter" style="display: inline-flex;">
                                                                    
                                                                    <label style="display: inline-flex;">Buscar:  
                                                                        <input style="position: relative;top: -10px;margin-left: 5px;" type="search" class="form-control search form-control-sm" placeholder="" aria-controls="tblList" value="{{$term ?? ''}}">
                                                                    </label>
                                                                </div>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                    <div id="listResult" class="relative" style="min-height: 380px;">
                                                        
                                                    </div>
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
@include('poll.sub.mdl_showResults')

@endsection

