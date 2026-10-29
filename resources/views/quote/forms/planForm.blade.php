<div class="modal-header">
    <h5 class="modal-title">CONFIGURACION DE AREAS Y PLANOS</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <form id="plan" action="quotes/update-area-plan" enctype="multipart/form-data">
        {{ method_field( isset($quote->id)?'POST':'POST') }}
        {{ csrf_field() }}
        <input type="hidden" value="<?= $quote->id ?? ''; ?>" name="id" />
        <input type="hidden" name="enabled" value="1" />
        <!-- Basic tabs start -->
        <section id="basic-tabs-components">
            <div class="row match-height">
                <div class="col-lg-12 col-xl-12">
                    <div class="card-content">
                        <div class="container">
                            <ul class="nav nav-tabs">
                                @if(isset($quote->services))
                                @php $count = 0; @endphp
                                    @foreach($quote->services as $service)
                                        <li class="nav-item serviceControls-1">
                                            <a class="nav-link services {{$count == 0 ? 'active':''}}" id="baseIcon-tab1"
                                                data-toggle="tab" aria-controls="tabIcon1" href="#tab{{$service->id ?? ''}}"
                                                aria-expanded="true"
                                                data-id="{{$service->id}}" 
                                                data-click="setService"
                                                onclick="localStorage.setItem('currentServiceTab','#tab{{$service->id ?? ''}}')">
                                                <i data-toggle="tooltip" data-placement="top" title=""
                                                    class="icon-{{$service->service->avatar ?? ''}}">
                                                </i>&nbsp;
                                                {{$service->service->name ?? ''}} ({{$service->coverage ?? ''}})
                                            </a>
                                        </li>
                                    @php $count++; @endphp
                                @endforeach
                            </ul>
                            @endif

                            <div class="tab-content px-1 pt-3 underTabContent">
                                @php $count = 0; @endphp
                                @foreach($quote->services as $service)
                                <input type="hidden" name="services[{{$service->id}}][id]" value="{{$service->id}}" />
                                    <div role="tabpanel" class="tab-pane {{$count == 0 ? 'active':''}}" id="tab{{$service->id ?? ''}}" aria-expanded="true" aria-labelledby="baseIcon-tab1">
                                            <!-- incluir tabs de areas-->
                                            @include('quote.forms.areaForm')
                                    </div>
                                    @php $count++; @endphp
                                @endforeach
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Basic tabs start -->
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
    <button type="button" class="btn btn-success round"
    data-click="savePlan">{{isset($area->id)?'Actualizar':'Guardar'}}</button>
</div>