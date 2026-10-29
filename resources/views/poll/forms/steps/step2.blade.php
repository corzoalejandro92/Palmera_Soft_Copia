<h6><i class="step-icon ft-edit-3"></i>Encuesta</h6>
<fieldset class="fieldspace" id="fdate">
    <table class="table stripe tbl-question">
        <thead>
            <tr class="th_title">
                <th>Pregunta</th>
                <th style="width: 10px;"></th>
            </tr>
        </thead>
        <tbody>
            @if(isset($poll->questions))
                @foreach($poll->questions as $question)
                    @php
                        $key = uniqid();
                    @endphp
                    @include('poll.forms.frames.question_new_row',compact(['key','question']))
                @endforeach
            @else
                @php $key = uniqid(); $poll= null; @endphp
                @include('poll.forms.frames.question_new_row',compact('key'))
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="1" class="add_row_th" style="width: 10px;" colspan="2">
                    <a class="btn btn-outline-teal" data-click="addRowQuestion">
                        <i class="la la-plus-circle"></i>
                    </a> <span style="position: relative;top: 10px;left: 5px;">Agregar mas preguntas</span>
                    @include('layouts/loading/min')
                </th>
            </tr>
        </tfoot>
    </table>
</fieldset>