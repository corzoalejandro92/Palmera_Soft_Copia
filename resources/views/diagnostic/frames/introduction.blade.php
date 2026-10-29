<div class="card" style="margin-top: 10px;">    
    <div class="card-header">
        <h4 class="card-title">Introducción/Cabecera</h4>
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
                                <div class="row">
                                    @foreach ($blocks->where('slug','diagnostic-header') as $block)
                                <div class="col-12 block-template" data-diagnostic="{{$diagnostic->id}}" data-click="setBlockHeader" data-toggle="tooltip" data-id="{{$block->id}}" data-placement="top" title="{{$block->description}}">
                                        {{$block->name}}
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
                                            <textarea name="header" data-keyup="hiden-error" id="header" cols="30" rows="100" >
                                                {!! $diagnostic->header !!}
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
















