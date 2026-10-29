<div class="row">
    @foreach ($user->files as $document)
        <div class="col-4">
            <a class="filemanager" href="{{route('crms.showfile',['id'=>$document->id])}}" target="_blank">
                <p class="type">{{$document->fileType->name}} {{$document->observation ? '('.$document->observation .')' : ''}}</p>
                <span class="icon file f-{{$document->extension}}">{{$document->extension}}</span>
                <span class="name">{{$document->name}}</span>
                <span class="size">{{$document->size_text}}</span>
            </a>
        </div>
    @endforeach
</div>