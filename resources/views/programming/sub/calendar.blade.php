@section('viewjs', 'programming/all')

<section id="grid-options" class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Agenda de programación</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div id='external-events'>
                                    <h4>Eventos</h4>
                                    <div class="fc-events-container">
                                        <div class='fc-event' data-color='#2D95BF'>Reunion semanal</div>
                                        <div class='fc-event' data-color='#48CFAE'>Cita con Noel (Agosto)</div>
                                        <div class='fc-event' data-color='#50C1E9'>Visita propuesta contratos nuevo cliente</div>
                                        <div class='fc-event' data-color='#FB6E52'>Enviar correo Solla</div>
                                        <div class='fc-event' data-color='#ED5564'>Almuerzo inv. empresa</div>
                                        <div class='fc-event' data-color='#F8B195'>Permiso visita EPS</div>
                                        <div class='fc-event' data-color='#6C5B7B'>Desratización: Bimbo</div>
                                        <div class='fc-event' data-color='#355C7D'>Desinfección: Solla</div>
                                        <div class='fc-event' data-color='#547A8B'>Visita a grupo éxito</div>
                                        <div class='fc-event' data-color='#3EACAB'>Cambio encarpado Cootrasana</div>
<!--                                        <p>
                                            <input type='checkbox' id='drop-remove' />
                                            <label for='drop-remove'>remove after drop</label>
                                        </p>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div id='fc-external-drag'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>