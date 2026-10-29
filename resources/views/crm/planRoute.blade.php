@php 
$binary = base64_decode($file->base64);
$imageProp = getimagesizefromstring($binary);
$url=url('/').'/';
$v=1.0;
@endphp
<html>
    <head>
    <title>Plano {{$file->fileType->name}}</title>
        <meta name="url" content="{{url('')}}">
        <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.css")?>">
       <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/crm/mechanism.css">
       <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap-extended.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/colors.css")?>">

    <link rel="stylesheet" type="text/css" href="<?=url("template/assets/icons/svg/icon.css")?>">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/core/colors/palette-gradient.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/fonts/simple-line-icons/style.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/core/colors/palette-gradient.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/pages/timeline.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/plugins/file-uploaders/dropzone.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/scrollbar/jquery.mCustomScrollbar.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/fonts/line-awesome/css/line-awesome.min.css")?>">

    <link rel="stylesheet" type="text/css" href="<?= url("template/app-assets/css/plugins/ui/jqueryui.min.css") ?>">

   
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/assets/css/style.css")?>">
    <!-- END: Custom CSS-->

         <!--- inclusión permanente del css de usuarios para editar el perfil -->
         <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/user/form.css?v=<?=$v?>">
    
             <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/@yield('viewcss').css?v=<?=$v?>">
    
         <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
       <style>
           ul {
  list-style: none;
}

body{
    font-family: sans-serif;
}
#wrapper{
    min-height: 300px;
    border: 1px solid black;
    margin: 20px;
}

#box {

    height: 20px;
    background-color: #009666;
    padding: 6px;
    color: #fff;
}

#content {
  display: inline-block;
}

#content ul {
  padding-left: 10px;
  padding-top: 10px;
  display: inline-block;
}

#content li {
  width: 230px;
  float: left;

  margin: 0px 10px 10px 0px;
  font-size: 12px;

}


.spantext {
    position: absolute;
    text-shadow: rgb(255 255 255) 10px 11px 4px;
    color: rgb(0 0 0);
    font-weight: 900;
    z-index: 1000;
    border-radius: 1%; 
    font-size: 10px;
    background-color: rgb(0, 0, 0);
    color: rgb(255, 255, 255);
}

.btn{
    padding: 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-info{
    background: #34a5ff;
    color: #fff;
}
.footer{
    padding: 30px;
    color: #fff;
}

.avatar-shadow {
    border-radius: 50%;
    object-fit: cover;
    width: 30px;
    height: 30px;
    box-shadow: none;
    border: 1px solid #000;
}
</style>
    </head>
    <body style="background: #494545; margin:0">
        <div class="row" style="background: #fcfcfc; width:{{$imageProp[0]+30}}px;padding-bottom: 1px;" id="realcontainer">
            
            <div class="col-lg-12 col-md-12"  id="cardImage">
                <div class="card text-center ">
                   
                        <div class="card-body" style="padding: 0px!important;">
                            <div class="col-md-12" style="margin-top: 40px;" >
                                <b><h5 style="color: #000;
                                    font-weight: 900;
                                    text-transform: uppercase;">{{$file->fileType->name}} {{$file->filable->name}}</h5></b>
                               
                                <hr>
                            </div>
                        <div id="imageCanvas" class="plan" style="background-image: url({{'data:image/jpeg;base64,' .$file->base64}}); height: {{$imageProp[1]}}px; width:{{$imageProp[0]}}px; position: relative;" >
                        @foreach ($file->extends as $extend)
                            @php
                                    $xmargin = $extend->size /10;
                                    $ymargin = $extend->size /40;
                                    $title= $type == 'Mecanismos' ? $extend->extendible->name : $extend->extendible->name. ' - '.$extend->extendible->description;
                            @endphp
                            <img data-id="{{$extend->id}}" data-click="recordElement" src="{{$extend->extendible->full_avatar}}"  data-plan= "{{$file->id}}" data-id="{{$extend->id}}" data-y="{{$extend->y}}" data-num="{{$extend->num}}" data-x="{{$extend->x}}" data-size="{{$extend->size}}" data-type="{{$extend->extendible->id}}" data-title="{{$type}}" class="avatar-shadow mechanismP delete{{$extend->id}} ui-draggable ui-draggable-handle mCS_img_loaded" title="{{$title}}" alt="{{$extend->extendible->slug}}" style="position: absolute; top: {{$extend->y}}%; left: {{$extend->x}}%; height: {{$extend->size}}px!important; width:  {{$extend->size}}px!important;">
                            
                            {{-- @if($type == 'Mecanismos') --}}
                                <span class="spantext deleteT{{$extend->id}}" style="position: absolute; top: {{$extend->y +$ymargin}}%; left: {{$extend->x+$xmargin}}%;width:auto;background-color:#000;color:#fff;border-radius:0px;font-size:0.6rem; padding:1px"><span style="color: yellowgreen">
                                    {{$extend->num}} -
                                </span>{{$extend->extendible->slug}}</span>
                                
                               
                               
                            {{-- @endif --}}
                        @endforeach
                            <div class="dropdown" style="z-index:100000!important;">
                                <ul class="dropdown-menu" role="menu" >
                                    <div id="options">
                                    </div>
                                </ul>
                            </div>
                    </div>
            <div id="wrapper" style="min-height: 88px;">

                <div id="box" style="height: 30px!important">
                  <span>Tabla de convenciones<br></span>
                  <div id="content">
                    <ul>
                        @foreach($elements as $element)
                            <li style="display: flex;"><<i class="las la-long-arrow-alt-right" style="color: {{$element->avatar}};font-size: 35px;"></i> &nbsp;
                                <span class="avatar-span" style="white-space: pre-wrap;  margin: auto;margin-left: 2px;    color: #000;"><b>{{$element->slug}}:</b> {{$element->name}}</span>
                            
                            </li>
                        @endforeach
                    </ul>
                  </div>
                
                </div>
    
            </div>
            <div class="col-md-12">
            
            
                <small style="color:#ccc; margin-bottom:20px; display: block">
                    Exportado el {{date('Y-m-d')}}, {{config('app.name')}}
                    <br>
                </small>
            </div>
        </div>
        <div class="footer">
            <label style="color: #222">Opciones de visualización:</label>
            <button class="btn btn-info" onclick="exportPlan();"> Exportar a JPG</button>
        </div>
    </div>
        

        
    @include('crm.sub.mdl_formShow')
    </body>
</html>
 <!-- BEGIN: Vendor JS-->
    <script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>"> type="text/javascript"></script>
    <script src="<?=url("assets/js/layouts/app.js")?>" type="text/javascript"> </script>
    <!-- BEGIN Vendor JS-->
<script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>" type="text/javascript"></script>
<script src="{{url("template/app-assets/vendors/js/extensions/sweetalert.min.js")}}" type="text/javascript"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js" type="text/javascript"></script>

<script>

    function exportPlan(){

        var width = $(".card-body").width();
        var height = $(".card-body").height();

        setTimeout(() => {
            html2canvas(document.querySelector(".card-body"),{
                scrollX: 0,
                scrollY: -window.scrollY,
            height: height,
            width: width,
            
        }).then(canvas => {
           // Cuando se resuelva la promesa traerá el canvas
            // Crear un elemento <a>
            let enlace = document.createElement('a');
            enlace.download = "{{$file->fileType->name}}.png";
            // Convertir la imagen a Base64
            enlace.href = canvas.toDataURL();
            // Hacer click en él
            enlace.click();
        });
        }, 200);
        
    }

function download(strData, strFileName, strMimeType) {

var D = document,
A = arguments,
a = D.createElement("a"),
d = A[0],
n = A[1],
t = A[2] || "text/plain";

//build download link:
a.href = "data:" + strMimeType + "," + escape(strData);
a.click();
}

</script>
<script>
const ULR = $('meta[name="url"]').attr('content') +"/";

$('body').on('click', "[data-click='recordElement']", function() {
    var id = $(this).data('id');
    var mdlForm = $('#mdlFormShow');
    $('.modal-content', mdlForm).html('');

    var loading = $('.loading', mdlForm).first();
    loading.attr('data-show', 'true');
    mdlForm.modal('show');
    loadRecord(id).done(function(data) {
        $('.modal-content', mdlForm).html(data.view);
    }).fail(function(error) {
        handleErrors(error);
    });

});
function loadRecord(id) {
    return $.ajax({
        url: ULR + "crm/record/"+id,
        method: "GET",
        dataType: "json",
    });
}
</script>