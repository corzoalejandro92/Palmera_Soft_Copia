<?php $v='1.0';  ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Canvas Editor</title>
        <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/crm/canvas.css?v=<?=$v?>" media="">
    </head>
    <body>
        <div style="display: inline-flex;">
            <div id="container">
                <canvas id="canvas" width="1000px" height="500"></canvas>
            </div>
            <div class="card"  style="margin: 5vh;">
                <div class="card-body">
                    <h4>{{$type == 'Mecanismos' ? 'Convecciones' : $type}}</h4>
                        <ul class="list-mechanism" style="height: 500px;overflow-y: scroll;">
                            @foreach($elements as $element)
                            <li class="">
                                <div class="row">
                                    <div class="col-md-2">
                                        <img 
                                            src="{{$element->full_avatar}}" 
                                            data-element=  "{{$element->id}}"
                                            data-plan= "",
                                            data-type= "{{$element->id}}"
                                            class="avatar-shadow mechanism" 
                                            title="{{$element->name}}" 
                                            alt="{{$element->name}}">
                                    </div>
                                    <div class="col-md-9">
                                        <span class="avatar-span">{{$element->name}}</span>
                                    </div>
                                </div>
                            </li>
                            @endForeach
                        </ul>
                </div>
            </div>
        </div>
       
        <script src="<?= url('/') ?>/assets/js/crm/canvas.js?v={{$v}}"></script>
    </body>
</html>