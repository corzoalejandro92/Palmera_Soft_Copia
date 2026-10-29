<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<?php
    $v=1.0;
?>
<head>
    <meta name="url" content="{{url('')}}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="Sistema administrativo Palmera Junior S.A.S.">
    <meta name="keywords" content="Palmera Junior">
    <meta name="author" content="ThemeSelect">
    
    <title>PJS | @yield('title','')</title>

    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/components.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/core/menu/menu-types/vertical-menu-modern.css")?>">


    <link rel="apple-touch-icon" href="<?=url("template/app-assets/images/logo/logo-min.png")?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?=url("template/app-assets/images/logo/logo-min.png")?>">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/vendors.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/charts/chartist.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/charts/chartist-plugin-tooltip.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/file-uploaders/dropzone.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/forms/icheck/icheck.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/forms/icheck/custom.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/vendors/css/timeline/vertical-timeline.css")?>">
    <!-- END: Vendor CSS-->

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

    @if(View::hasSection('jqueryUI'))
    <link rel="stylesheet" type="text/css" href="<?= url("template/app-assets/css/plugins/ui/jqueryui.min.css") ?>">
    @endif

   
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/assets/css/style.css")?>">
    <!-- END: Custom CSS-->

         <!--- inclusión permanente del css de usuarios para editar el perfil -->
         <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/user/form.css?v=<?=$v?>">
    
         @if(View::hasSection('viewcss'))
             <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/@yield('viewcss').css?v=<?=$v?>">
         @endif
    
         <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body  data-open="click" data-menu="vertical-menu-modern" data-color="bg-gradient-x-green" data-col="2-columns">

   
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
          
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Vendor JS-->
    <script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>"> type="text/javascript"></script>
    <script src="<?=url("assets/js/layouts/app.js")?>" type="text/javascript"> </script>
    <!-- BEGIN Vendor JS-->


    <!-- BEGIN: Theme JS-->
    <script src="<?=url("template/app-assets/js/core/app-menu.js")?>" type="text/javascript"></script>
    <script src="<?=url("template/app-assets/js/core/app.js")?>" type="text/javascript"></script>
    <!-- END: Theme JS-->

    @if(View::hasSection('jqueryUI'))
        <script src="<?= url("template/app-assets/js/core/libraries/jquery_ui/full.min.js") ?>" type="text/javascript"></script>
    @endif

   
    @if(View::hasSection('ckeditor'))
        <script src="<?= url("template/app-assets/vendors/js/editors/ckeditor/ckeditor.js") ?>" type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('viewjs'))
        <script src="<?= url('/') ?>/assets/js/@yield('viewjs').js?v=<?=$v?>"></script>
    @endif
    
    @if(View::hasSection('viewjs2'))
        <script src="<?= url('/') ?>/assets/js/@yield('viewjs2').js?v=<?=$v?>"></script>
    @endif

  

</body>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-light navbar-border navbar-shadow">
        <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block"><?=date('Y')?> &copy; Todos los derechos reservados <a class="text-bold-800 grey darken-2" href="http://www.palmerajunior.com" target="_blank">Palmera Junior S.A.S. &REG;</a></span>
            <ul class="list-inline float-md-right d-block d-md-inline-blockd-none d-lg-block mb-0">
                <li class="list-inline-item">Desarrollado por <a class="my-1" href="https://softwarewebsas.com/" target="_blank"> Software Web S.A.S.</a></li>
            </ul>
        </div>
    </footer>
    <!-- END: Footer-->
<!-- END: Body-->
@include('layouts.sub.mdl_form')
</html>
