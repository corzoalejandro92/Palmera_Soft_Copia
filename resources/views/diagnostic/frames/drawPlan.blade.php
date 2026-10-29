<div class="card">    
    <div class="card-header">
        <h4 class="card-title">Plano de de influencia y presión externa</h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <!--<li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>-->
                <li class="li-plan"><a data-action="collapse" class="collapsed"><i class="ft-plus"></i></a></li>
                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
            </ul>
        </div>
    </div>
    <script>
        localStorage.setItem('menuLocked',false);
        localStorage.setItem('defaultZoom',false);
        document.body.style.zoom = "90%";
    
    </script>
    
    <div class="content-plan card-content collapse">
        <div class="card-body" align="center">
                @if(isset($quote->id))
                    <a target="_blank" data-click="plan" style="display: {{!$diagnostic->file ? 'none':'initial'}}" href="{{route('diagnostic.edit-paint',["quote" => $quote->id , "plan" => isset($diagnostic->file->id) ? $diagnostic->file->id : '0'])}}" class="btn btn-info btn-lg edit">
                @else
                    <a target="_blank" data-click="plan" style="display: {{!$diagnostic->file ? 'none':'initial'}}" href="{{route('diagnostic.edit-paintUser',["plan" => isset($diagnostic->file->id) ? $diagnostic->file->id : '0'])}}" class="btn btn-info btn-lg edit">
                @endif
                <i class="la la-pen"></i>
                    Editar Plano
                </a>
                
                @if(isset($quote->id))
                    <a  class="btn btn-success btn-md create" style="display: {{$diagnostic->file ? 'none':'initial'}}" target="_blank" data-click="plan" href="{{route('diagnostic.paint',["quote"=>$quote->id])}}">
                @else
                    <a  class="btn btn-success btn-md create" style="display: {{$diagnostic->file ? 'none':'initial'}}" target="_blank" data-click="plan" href="{{route('diagnostic.paintUser',["diagnostic" => $diagnostic->id ])}}">
                @endif
                    <i class="la la-pen"></i>
                    Comezar a dibujar
                </a> 
     

            <img src="{{ isset($diagnostic->file ) &&$diagnostic->file->path ? '/public/storage/'.$diagnostic->file->path : '/images/default/base_plano_diagnostico_f.png'}}" style="width: 60%;">
            
        </div>
    </div>
</div>


















