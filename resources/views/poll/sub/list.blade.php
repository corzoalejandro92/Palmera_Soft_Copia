<table class="table table-striped table-bordered zero-configuration" id="tblList">
    <thead>
        <tr>
            <th  class="text-center">Nombre</th>
            <th class="text-center">Descripción</th>
            <th class="text-center">Preguntas</th>
            <th class="text-center">Habilitada</th>
            <th class="text-center nofilter">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($polls as $poll): ?>
        <tr class="text-center">
            <td>
                <div>
                    <?= $poll->title ?>
                </div>
            </td>
            <td class="text-center">{!!$poll->observation ?? 'Vacio'!!}</td>
            <td>
                @foreach($poll->questions as $question)
                <span class="badge badge-info m-3px">{{$question->question}}</span>
                @endforeach
            </td>
            <td>
                <span class="badge badge-{{ $poll->enabled ? 'success' : 'dark'}}"> {{ $poll->enabled ?  'Si' : 'No'}}</span>
            </td>
            <td style="width: 120px;">
                <div class="text-center">
                    <a href="{{route('polls.edit',[$poll->id])}}"><i class="icon-pencil mx-0 " data-click="openForm"
                            data-id="<?= $poll->id ?>"></i></a>
                    <a href="#"><i class="icon-trash mx-1 text-danger" data-click="openFormDelete"
                            data-id="<?= $poll->id ?>"></i></a>
                </div>
            </td>
        </tr>
        <?php 
        endforeach;
        ?>
    </tbody>
</table>