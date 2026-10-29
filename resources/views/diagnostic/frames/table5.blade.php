<div class="card">    
    <div class="card-header">
        <h4 class="card-title">Tabla 5: identificacion de plagas y su grado de infestación </h4>
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
                
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Diseño de la plantilla</h4>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea name="table5" data-keyup="hiden-error" id="table5" cols="30" rows="45">
                                                    @if($diagnostic->identification)
                                                    {!!$diagnostic->identification!!}
                                                @else
                                                <table border="1" id="table5" cellpadding="1" cellspacing="1" style="width:100%">
                                                    <caption>
                                                    @php
                                                        $crmName = isset($quote->crm->name) ? $quote->crm->name : $diagnostic->Office->crm->name;
                                                    @endphp
                                                    <p><strong>Tabla No. 5 IDENTIFICACIÓN DE PLAGAS Y SU GRADO DE INFESTACIÓN EN LAS ÁREAS DE {{ strtoupper($crmName ?? '') }}</strong></p>
                                                
                                                    <p>&nbsp;</p>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align:center"><strong>N°</strong></td>
                                                            <td style="text-align:center"><strong>AREAS</strong></td>
                                                            <td style="text-align:center"><strong>TIPO DE PLAGA AVISTAMIENTO</strong></td>         
                                                            <td style="text-align:center"><strong>GRADO DE INFESTACIÓN AÑO 2xxx</strong></td>
                                                            <td style="text-align:center"><strong>GRADO DE INFESTACIÓN AÑO 2xxx</strong></td>                                                   
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align:center"></td>
                                                            <td style="text-align:center"></td>
                                                            <td style="text-align:center"></td>         
                                                            <td style="text-align:center"></td>
                                                            <td style="text-align:center"></td>                                                   
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
















