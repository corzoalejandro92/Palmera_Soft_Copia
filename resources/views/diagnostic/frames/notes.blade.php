<div class="card">    
    <div class="card-header">
        <h4 class="card-title">Notas/Pie del diagnóstico</h4>
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
                                            @php
                                                $crmName = isset($quote->crm->name) ? $quote->crm->name : $diagnostic->Office->crm->name;
                                            @endphp
                                            <textarea name="footer" data-keyup="hiden-error" id="footer" cols="30" rows="45" >
                                                @if($diagnostic->footer)
                                                {!!$diagnostic->footer!!}
                                                @else
                                                    <p style="margin-left:0pt; margin-right:0pt; text-align:justify"><span style="font-family:Arial"><strong><span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222"><strong>NOTA:</strong></span></span></span></span></strong><span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">&nbsp;Las fotos aqu&iacute; publicadas son tomadas con autorizaci&oacute;n de la empresa </span></span></span></span><strong><span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222"><strong> {{ strtoupper($crmName) }} </strong></span></span></span></span></strong><span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">y manejadas con las pol&iacute;ticas de</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">confidencialidad</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">de la empresa PALMERA JUNIOR S.A.S.; por lo que</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">&eacute;ste</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">informe est&aacute; prohibido divulgarlo, revelarlo, entregarlo, transmitirlo o presentarlo a terceros, entidades u otras empresas de fumigaci&oacute;n y/o control de plagas; &uacute;nicamente se</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">presentar&aacute;</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">ante</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">las autoridades sanitarias</span></span></span></span>&nbsp;<span style="font-size:11,0000pt"><span><span style="font-family:Arial"><span style="color:#222222">que lo requieran.</span></span></span></span></span></p>

                                                    <p style="margin-left:0pt; margin-right:0pt; text-align:justify">&nbsp;</p>

                                                    <p style="margin-left:0pt; margin-right:0pt; text-align:justify"><span style="font-family:&quot;News Gothic MT&quot;"><strong><span style="font-size:11,0000pt"><span style="font-family:Arial"><strong>ESTAMOS AUTORIZADOS POR LA SECRETARIA DE SALUD DE SANTANDER CONTAMOS CON OPERARIOS Y EQUIPOS ESPECIALIZADOS Y TENEMOS EXPERIENCIA EN EL CONTROL DE AN&Aacute;LISIS DE PELIGROS Y CONTROL EN PUNTOS CR&Iacute;TICOS (HACCP)- ISO- BPM.</strong></span></span></strong></span></p>

                                                    <p style="margin-left:0pt; margin-right:0pt; text-align:justify">&nbsp;</p>

                                                    <p style="margin-left:0pt; margin-right:0pt; text-align:justify"><span style="font-family:&quot;News Gothic MT&quot;"><span style="font-size:11,0000pt"><span style="font-family:Arial">Atentamente,</span></span></span></p>

                                                    <p style="margin-left:0pt; margin-right:0pt; text-align:justify"><span style="font-family:Arial"><img src="{{Auth::user()->signature}}" style=" width:200px" /></span></p>

                                                    <p style="margin-left:0pt; margin-right:0pt; text-align:justify"><span style="font-family:&quot;News Gothic MT&quot;"><span style="font-size:11,0000pt"><span style="font-family:Arial">{{strtoupper(Auth::user()->name)}}</span></span></span><br />
                                                     <span style="font-family:&quot;News Gothic MT&quot;"><span style="font-size:11,0000pt"><span style="font-family:Arial">{{Auth::user()->position}}</span></span></span></p>

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
















