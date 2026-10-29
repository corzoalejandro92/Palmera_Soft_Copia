<div class="card">    
    <div class="card-header">
        <h4 class="card-title">Tabla 1: Plagas reales</h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <!--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>-->
                <li><a data-action="collapse" class="collapsed"><i class="ft-plus"></i></a></li>
                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse">
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Ayudas</h4>
                                <div class="row" style="overflow:auto; max-height: 600px">
                                    @foreach ($controlInfestations as $infestation)
                                        <div class="col-12 block-template" style="line-height: 30px;" data-click="setInfestation" data-toggle="tooltip" data-id="{{$infestation->id}}" data-placement="top" title="{{$infestation->name}}">
                                            <img class="avatar avatar-50" src="{{url($infestation->avatar ?? 'images/default/plaga.png') }}">   {{$infestation->name}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-10">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Diseño de la plantilla</h4>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea name="table1" data-keyup="hiden-error" id="table1" cols="30" rows="45">
                                                @if($diagnostic->real_infestations)
                                                    {!!$diagnostic->real_infestations!!}
                                                @else
                                                <table border="1" id="table1" cellpadding="1" cellspacing="1" style="width:100%">
                                                    <caption>
                                                    <p><strong>Tabla No. 1 PLAGAS REALES</strong></p>
                                                
                                                    <p>&nbsp;</p>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align:center"><strong>PLAGA</strong></td>
                                                            <td style="text-align:center"><strong>BIOLOG&Iacute;A</strong></td>
                                                            <td style="text-align:center"><strong>AREAS</strong></td>
                                                            <td style="text-align:center"><strong>SISTEMA DE<br />
                                                            CONTROL</strong></td>
                                                            <td style="text-align:center"><strong>FRECUENCIA</strong></td>
                                                            <td style="text-align:center"><strong>PRODUCTO</strong></td>
                                                            <td style="text-align:center"><strong>POSIBLES CAUSAS<br />
                                                            DE SU PRESCENCIA</strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <p>&nbsp;</p>
                                                @endif
                                            </textarea>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
















