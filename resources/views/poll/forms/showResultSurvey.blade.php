<div class="modal-header">
    <h5 class="modal-title"><b>Ver Encuesta => {{$answerSurvey->id ?? ''}}</b></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="container">
        <h5 class="text-center"><b>{{$answerSurvey->poll->title}}</b><br><br><br>
        <span class="text-muted">{!!$answerSurvey->poll->observation !!}</span></h5>
        @foreach ($answerSurvey->detailsAnswer as $index => $detail)
            <div class="form-group">
                <h5> - {{$detail->question->question}} <b>R/: <b>{{$detail->answer->answer}}</b></h5>
            </div>
       @endforeach
        <br><hr>
        <h5 style="color: #acacac;"><b>Fecha:</b> {{$answerSurvey->created_at->format('d-m-Y H:i:s')}}</h5>
        <h5 style="color: #acacac;"><b>Usuario/Tecnico:</b> {{$answerSurvey->user->name}}</h5>
        <h5 style="color: #acacac;"><b>Cliente:</b> {{$answerSurvey->office->crm->name}}</h5>
        <h5 style="color: #acacac;"><b>Sucursal:</b> {{$answerSurvey->office->name}}</h5>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary round" data-dismiss="modal">Cerrar</button>
</div>