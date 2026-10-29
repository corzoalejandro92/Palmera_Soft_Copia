<div class="card">    
    <div class="card-header">
        <h4 class="card-title">Tabla 3: Métodos de control</h4>
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
                <div class="col-3">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Ayudas</h4>
                                <div class="row" style="overflow:auto; max-height: 600px">
                                    
                                    @foreach ($applications as $application)
                                        <div class="col-12 block-template" style="line-height: 30px;" data-click="setMethods" data-toggle="tooltip" data-id="{{$application->id}}" data-placement="top" title="{{$application->name}}">
                                            <img class="avatar avatar-50" src="{{url($application->avatar ?? 'images/default/method.png' )}}">   {{$application->name ?? ''}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Diseño de la plantilla</h4>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea name="table3" data-keyup="hiden-error" id="table3" cols="30" rows="45">
                                                @if($diagnostic->control_methods)
                                                    {!!$diagnostic->control_methods!!}
                                                @else
                                                <table border="1" id="table3" cellpadding="1" cellspacing="1" style="width:100%">
                                                    <caption>
                                                    <p><strong>Tabla No. 3 MÉTODOS DE CONTROL</strong></p>
                                                
                                                    <p>&nbsp;</p>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align:center"><strong>M&Eacute;TODO</strong></td>
                                                            <td style="text-align:center"><strong>DESCRIPCI&Oacute;N</strong></td>                                                           
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
















