
<tr data-id="<?=$key?>">
    @if($question)
        <input type="hidden" name="question[<?=$key?>][id]" value="{{$question->id ?? ''}}" />
    @endif
    <td style="width: 95%">
            <input class="form-control" type="text" name="question[<?=$key?>][question]" value="<?=$question->question ?? '' ?>" placeholder="¿ Ingresa la pregunta ?">
    </td>
    <td class="add_row_th" style="width: 10px;">
        <a class="btn btn-outline-danger" data-click="removeQuestionRow" data-key="<?=$key?>">
            <i class="la la-trash-o"></i>
        </a>
    </td>
</tr>
<tr style="display: grid;padding-bottom: 60px;" data-id="<?=$key?>">
    <td>
        <table class="table responsive" style="margin-left: 20px;">
            <thead>
                <tr>
                    <th colspan="3">Respuestas</th>
                </tr>
            </thead>
            <tbody id="answerContent_{{$key}}">
                @if(isset($question->answers))
                    @foreach($question->answers as $answer)
                        @php
                            $skey = uniqid();
                        @endphp
                        @include('poll.forms.frames.answer_new_row',compact(['key','answer','skey']))
                    @endforeach
                @else
                    @php $skey = uniqid(); @endphp
                    @include('poll.forms.frames.answer_new_row',compact(['skey', 'key']))
                @endif
            </tbody>
        </table>
        <hr>
    </td>
</tr>





