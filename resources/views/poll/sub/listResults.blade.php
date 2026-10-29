<table class="table table-striped table-bordered zero-configuration" >
    <thead>
        <tr>
            <th  class="text-center">#</th>
            <th class="text-center">Encuesta</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Sucursal</th>
            <th class="text-center">Servicio</th>
            <th class="text-center">Tecnico/Usuario</th>
            <th class="text-center">Fecha</th>
            <th class="text-center nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($answersSurveys as $answerSurvey)
            <tr class="text-center">
                <td>
                    <div>
                        {{$answerSurvey->id ?? ''}}
                    </div>
                </td>
                <td class="text-center">
                    {{$answerSurvey->poll->title ?? ''}}
                </td>
                <td>
                    {{$answerSurvey->office->crm->name ?? ''}} {{$answerSurvey->office->crm->commercial_name ?? ''}}
                </td>
                <td>
                    {{$answerSurvey->office->name ?? ''}}
                </td>
                <td>
                    {{$answerSurvey->crmSchedule->service->name ?? ''}}
                </td>
                <td>
                    {{$answerSurvey->user->name ?? ''}}
                </td>
                <td>
                    {{$answerSurvey->created_at ?? ''}}
                </td>
                <td class="text-center" style="width:120px">
                    <div>
                        <a title="Ver encuesta" href="#"><i class="icon-eye mx-0 text-info" data-click="openFormResults" data-id="{{$answerSurvey->id}}"></i></a>
                            @if(Auth::user()->roles->pluck('slug')->contains('root'))
                                <a title="Eliminar encuesta" href="#"><i class="la la-trash-o text-danger" data-click="deleteSurveys"
                                data-id="{{$answerSurvey->id}}"></i></a>
                            @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<span align="left" style="margin-top: -58px;">Mostrando del {{$answersSurveys->firstItem()}}  al {{$answersSurveys->lastItem()}} de {{$answersSurveys->total()}} resultados</span>
<div align="right" style="margin-top: -20px;">
    {!!$answersSurveys->render()!!}
</div>