<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<?php
    $v=config('app.version');
    use App\General\Menu;
?>
<head>
        <!--- inclusión permanente de onsignal para notificaciones push -->
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async defer></script>
        <script>
            IS_CUSTOMER = 1;
            APP_VERSION = '{{$v}}';
            @can('crms.info.admin')
                IS_CUSTOMER =  0;
            @endcan
            let externalUserIdAuthHash = "{{hash_hmac('sha256', Auth::User()->id, config('onesignal.rest_api_key'))}}"; 

            window.OneSignal = window.OneSignal || [];
            OneSignal.push(function() {
                OneSignal.init({
                    allowLocalhostAsSecureOrigin: true,
                    appId: "{{config('onesignal.app_id')}}",
                    promptOptions: {
                        slidedown: {
                            enabled: true,
                            autoPrompt: true,
                            timeDelay: 5,
                            pageViews: 1
                        },
                        actionMessage: 'Necesitamos enviarte notificaciones a este dispositivo',
                        acceptButtonText : 'Continuar',
                        cancelButtonText : 'No, Gracias'
                    }
                });

            });
        </script>
        <script>
            window.OneSignal = window.OneSignal || [];
            OneSignal.push(function() {
                OneSignal.on('subscriptionChange', function(isSubscribed){
                    console.log("el estado de subscripción del usuario es:" + isSubscribed);

                    OneSignal.sendTag('user_id','{{Auth::User()->id}}',function(tagSend){
                        console.log('Tag user_id fue enviado');
                    });

                    OneSignal.sendTag('name','{{Auth::User()->name}}',function(tagSend){
                        console.log('Tag name fue enviado');
                    });

                    OneSignal.sendTag('email','{{Auth::User()->email}}',function(tagSend){
                        console.log('Tag email fue enviado');
                    });

                    OneSignal.sendTag('phone','{{Auth::User()->phone}}',function(tagSend){
                        console.log('Tag phone fue enviado');
                    });


                    OneSignal.setExternalUserId('{{Auth::User()->id}}',externalUserIdAuthHash, function(){
                            console.log('external user id has sent successful');
                    });
                });

            });
        </script>

        @if(Session::has('recent_logged'))
            <script>
                window.OneSignal = window.OneSignal || [];
                OneSignal.push(function() {

                    OneSignal.sendTag('user_id','{{Auth::User()->id}}',function(tagSend){
                        console.log('Tag user_id fue enviado');
                    });
                    
                    OneSignal.sendTag('name','{{Auth::User()->name}}',function(tagSend){
                        console.log('Tag name fue enviado');
                    });

                    OneSignal.sendTag('email','{{Auth::User()->email}}',function(tagSend){
                        console.log('Tag email fue enviado');
                    });

                    OneSignal.sendTag('phone','{{Auth::User()->phone}}',function(tagSend){
                        console.log('Tag phone fue enviado');
                    });


                    OneSignal.setExternalUserId('{{Auth::User()->id}}',externalUserIdAuthHash, function(){
                            console.log('external user id has sent successful');
                    });
                    @php Session::forget('recent_logged'); @endphp
                });
            </script>
        @endif

        
        

    <meta name="url" content="{{url('')}}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="Sistema administrativo Palmera Junior S.A.S.">
    <meta name="keywords" content="Palmera Junior">
    <meta name="author" content="ThemeSelect">
    
    <title>PJS | @yield('title','')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/components.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/core/menu/menu-types/vertical-menu-modern.css")?>">


    <link rel="apple-touch-icon" href="<?=asset("template/app-assets/images/logo/logo-min.png")?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?=asset("template/app-assets/images/logo/logo-min.png")?>">


    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/vendors.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/charts/chartist.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/charts/chartist-plugin-tooltip.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/file-uploaders/dropzone.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/forms/icheck/icheck.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/forms/icheck/custom.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/timeline/vertical-timeline.css")?>">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/bootstrap.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/bootstrap-extended.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/colors.css")?>">

    <link rel="stylesheet" type="text/css" href="<?=asset("template/assets/icons/svg/icon.css")?>">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/core/colors/palette-gradient.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/fonts/simple-line-icons/style.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/core/colors/palette-gradient.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/pages/timeline.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/css/plugins/file-uploaders/dropzone.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/vendors/css/scrollbar/jquery.mCustomScrollbar.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=asset("template/app-assets/fonts/line-awesome/css/line-awesome.min.css")?>">

    @if(View::hasSection('jqueryUI'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/plugins/ui/jqueryui.min.css") ?>">
    @endif

    @if(View::hasSection('fullcalendar'))
        <link href="<?= asset("template/app-assets/vendors/css/calendars/fullcalendar.min.css") ?>" rel="stylesheet" type="text/css" />
        <link href="<?= asset("template/app-assets/css/plugins/calendars/fullcalendar.css") ?>" rel="stylesheet" type="text/css" />
    @endif
    
    @if(View::hasSection('dashboard'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/pages/dashboard-analytics.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/pages/dashboard-ecommerce.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/pages/advanced-cards.min.css") ?>">
    @endif

    @if(View::hasSection('toastr'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/extensions/toastr.css") ?>">
    @endif
    
    @if(View::hasSection('wizard'))
     <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/plugins/forms/wizard.css") ?>">
    @endif
    
    @if(View::hasSection('icheck'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/forms/icheck/icheck.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/forms/icheck/custom.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/plugins/forms/checkboxes-radios.css") ?>">
    @endif
    
    @if(View::hasSection('switch'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/forms/toggle/switchery.min.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/plugins/forms/switch.min.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/core/colors/palette-switch.min.css") ?>">
    
        <script src="<?= asset("template/app-assets/js/scripts/forms/switch.min.js") ?>" type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('datepicker'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/pickers/daterange/daterangepicker.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/pickers/pickadate/default.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/pickers/pickadate/default.date.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/pickers/pickadate/default.time.css") ?>">
    @endif

    @if(View::hasSection('timepicker'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/pickers/mdtimepicker/mdtimepicker.min.css") ?>">
         <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/pickers/mdtimepicker/mdtimepicker-theme.css") ?>">
    @endif
    
    @if(View::hasSection('select2'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/forms/selects/select2.min.css") ?>">
    @endif

    @if(View::hasSection('summernote'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/summernote/summernote.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/summernote/summernote.min.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/summernote/summernote-bs4.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/summernote/summernote-bs4.min.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/summernote/summernote-lite.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/summernote/summernote-lite.min.css") ?>">
    @endif

    @if(View::hasSection('datatables'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/tables/datatable/datatables.min.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/bootstrap-select.min.css") ?>">
    @endif

    @if(View::hasSection('lightbox2'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/lightbox2/src/css/lightbox.css") ?>">

    @endif

    @if(View::hasSection('wPaintPainterro'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/wPaintPainterro/css/styles.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/wPaintPainterro/css/bar-styles.css") ?>">
    @endif

    

    @if(View::hasSection('tagsinput'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/plugins/forms/tags/tagging.css") ?>">

    @endif

    @if(View::hasSection('wickedpicker'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/wickedpicker/stylesheets/wickedpicker.css") ?>">
    @endif

    @if(View::hasSection('contextMenus'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/contextMenu/dist/jquery.contextMenu.min.css") ?>">

    @endif

    @if(View::hasSection('graficas'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/plugins/charts/chartist.css") ?>">

    @endif

    @if(View::hasSection('fullcalendar2'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/css/core-4.3.1/main.min.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/css/timegrid-4.3.0/main.min.css") ?>">
    @endif

    @if(View::hasSection('fullcalendar4'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/fullcalendar4/core/main.min.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/fullcalendar4/daygrid/main.min.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/fullcalendar4/timegrid/main.min.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/fullcalendar4/timeline/main.min.css") ?>">
    @endif

    

    @if(View::hasSection('fuelux'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/fuelux/dist/css/fuelux.css") ?>">

    @endif

    @if(View::hasSection('wPaint'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/wPaint/wPaint.min.css") ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/js/wPaint/lib/wColorPicker.min.css") ?>">
    @endif


    @if(View::hasSection('timefile'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/pages/timeline.css") ?>">
    @endif

    @if(View::hasSection('colorpicker'))
    <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/css/plugins/pickers/colorpicker.css") ?>">
    @endif

    @if(View::hasSection('vistimeline'))
        <link rel="stylesheet" type="text/css" href="<?= asset("template/app-assets/vendors/css/timeline/vis-timeline.min.css") ?>">
    @endif
  
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?=asset("template/assets/css/style.css").'?v='.$v?>">
    <!-- END: Custom CSS-->

         <!--- inclusión permanente del css de usuarios para editar el perfil -->
         <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/user/form.css?v=<?=$v?>">
    
         @if(View::hasSection('viewcss'))
             <link rel="stylesheet" type="text/css" href="<?= url('/') ?>/assets/css/@yield('viewcss').css?v=<?=$v?>">
         @endif
         
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns   fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-color="{{config('app.barcolor')}}" data-col="2-columns">

    <!-- BEGIN: Header-->
        @include('layouts.header.navbar')
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
        @include('layouts.header.menu')
    <!-- END: Main Menu-->

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
    <script src="<?=asset("template/app-assets/vendors/js/vendors.min.js")?>"> type="text/javascript"></script>
    <script src="<?=asset("assets/js/layouts/app.js?v=<?=$v?>")?>" type="text/javascript"> </script>
    <!-- BEGIN Vendor JS-->

    @if(View::hasSection('wizard'))
        <script src="<?= asset("template/app-assets/vendors/js/extensions/jquery.steps.min.js") ?>" type="text/javascript"></script>
    @endif

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?=asset("template/app-assets/vendors/js/charts/chartist.min.js")?>" type="text/javascript"></script>
    <script src="<?=asset("template/app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js")?>" type="text/javascript"></script>
    <script src="<?=asset("template/app-assets/vendors/js/timeline/horizontal-timeline.js")?>" type="text/javascript"></script>
    <script src="<?=asset("template/app-assets/vendors/js/extensions/dropzone.min.js")?>" type="text/javascript"></script>
    <script src="<?=asset("template/app-assets/vendors/js/forms/validation/jquery.validate.min.js") ?>" type="text/javascript"></script>
    <script src="<?=asset("template/app-assets/vendors/js/scrollbar/jquery.mCustomScrollbar.concat.min.js") ?>" type="text/javascript"></script>

    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?=asset("template/app-assets/js/core/app-menu.js")?>" type="text/javascript"></script>
    <script src="<?=asset("template/app-assets/js/core/app.js")?>" type="text/javascript"></script>
    <!-- END: Theme JS-->

    @if(View::hasSection('jqueryUI'))
        <script src="<?= asset("template/app-assets/js/core/libraries/jquery_ui/full.min.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('contextmenu'))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
    @endif

    @if(View::hasSection('fullcalendar'))
        <script src="<?= asset("template/app-assets/vendors/js/extensions/moment.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/extensions/fullcalendar.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/extensions/fullcalendar.lang.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/contextMenu/dist/jquery.contextMenu.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/contextMenu/dist/jquery.ui.position.min.js") ?>"type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('sweetAlert'))
        <script src="<?= asset("template/app-assets/vendors/js/unpkg/promise-polyfill.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/extensions/sweet-alerts.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/extensions/sweetalert2.all.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('toastr'))
        <script src="<?= asset("template/app-assets/vendors/js/extensions/toastr.min.js") ?>" type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('dashboard'))
        <script src="<?= asset("template/app-assets/js/scripts/pages/dashboard-analytics.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/pages/dashboard-ecommerce.js") ?>" type="text/javascript"></script>
    @endif

    <!-- Customize js-->

    @if(View::hasSection('wPaint'))
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/lib/jquery.ui.core.1.10.3.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/lib/jquery.ui.widget.1.10.3.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/lib/jquery.ui.mouse.1.10.3.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/lib/jquery.ui.draggable.1.10.3.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/lib/wColorPicker.min.js") ?>" type="text/javascript"></script>   
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/wPaint.min.js") ?>" type="text/javascript"></script>

        <script src="<?= asset("template/app-assets/vendors/js/wPaint/plugins/main/wPaint.menu.main.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/plugins/text/wPaint.menu.text.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/plugins/shapes/wPaint.menu.main.shapes.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/wPaint/plugins/file/wPaint.menu.main.file.min.js") ?>" type="text/javascript"></script> 
    @endif

    @if(View::hasSection('lightbox2'))
        
        <script src="<?= asset("template/app-assets/vendors/js/lightbox2/src/js/lightbox.js") ?>"type="text/javascript"></script>

    @endif

    @if(View::hasSection('wPaintPainterro'))
        <script src="<?= asset("template/app-assets/vendors/js/wPaintPainterro/painterro-1.0.35.min.js") ?>"type="text/javascript"></script>
    @endif

    @if(View::hasSection('wickedpicker'))
        
        <script src="<?= asset("template/app-assets/vendors/js/wickedpicker/dist/wickedpicker.min.js") ?>"type="text/javascript"></script>

    @endif

    @if(View::hasSection('tagsinput'))
        
        <script src="<?= asset("template/app-assets/js/scripts/forms/tags/tagging.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/forms/tags/tagging.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/ui/prism.min.js") ?>"type="text/javascript"></script>
    @endif



    @if(View::hasSection('fuelux'))
        <script src="<?= asset("template/app-assets/vendors/js/fuelux/dist/js/fuelux.js") ?>"type="text/javascript"></script>

    @endif

    @if(View::hasSection('fullcalendar2'))
       <!-- <script src="<?php //asset("template/app-assets/vendors/js/fullcalendar2/dist/js/demo-to-codepen.js") ?>"type="text/javascript"></script> -->
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/core-4.3.1/core.main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/core-4.3.1/locales-all.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/daygrid-4.3.0/daygrid.main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/timegrid-4.3.0/timegrid.main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/resource-common-4.3.1/resource-common-main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/resource-daygrid-4.3.0/resource-daygrid-main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/resource-timegrid-4.3.0/timegrid-main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar2/dist/js/interaction-4.3.0/interaction-main.min.js") ?>"type="text/javascript"></script>
    @endif

    @if(View::hasSection('fullcalendar4'))
       <!-- <script src="<?php //asset("template/app-assets/vendors/js/fullcalendar2/dist/js/demo-to-codepen.js") ?>"type="text/javascript"></script> -->
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/core/main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/interaction/main.js") ?>"type="text/javascript"></script>  
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/daygrid/main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/timegrid/main.min.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/timeline/main.min.js") ?>"type="text/javascript"></script> 
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/core/common.main.js") ?>"type="text/javascript"></script>  
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/daygrid/resource.main.js") ?>"type="text/javascript"></script> 
        <script src="<?= asset("template/app-assets/vendors/js/fullcalendar4/timegrid/resource.main.js") ?>"type="text/javascript"></script>

        
        
    @endif
    
    @if(View::hasSection('icheck'))
        <script src="<?= asset("template/app-assets/vendors/js/forms/icheck/icheck.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/forms/checkbox-radio.js") ?>" type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('switch'))
        <script src="<?= asset("template/app-assets/vendors/js/forms/toggle/switchery.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/forms/switch.js") ?>" type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('datepicker'))
    <script src="<?= asset("template/app-assets/vendors/js/extensions/moment.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/pickers/pickadate/picker.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/pickers/pickadate/picker.date.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/pickers/pickadate/picker.time.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/pickers/daterange/daterangepicker.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/pickers/pickadate/legacy.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('timepicker'))
         <script src="<?= asset("template/app-assets/vendors/js/pickers/mdtimepicker/mdtimepicker.min.js") ?>" type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('dynamic-form'))
        <script src="<?= asset("template/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/forms/form-repeater.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('summernote'))
       <script src="<?= asset("template/app-assets/vendors/js/summernote/summernote.min.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('select2'))
        <script src="<?= asset("template/app-assets/vendors/js/forms/select/select2.full.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/forms/select/form-select2.js") ?>" type="text/javascript"></script>
    @endif

  
    @if(View::hasSection('datatables'))
    
        <script src="<?= asset("template/app-assets/vendors/js/bootstrap-select.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/defaults-es_ES.js") ?>"type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/tables/datatable/datatables.min.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/js/scripts/tables/datatables/datatable-api.js") ?>" type="text/javascript"></script>
        <script src="<?= asset("template/app-assets/vendors/js/tables/datatable/dataTables.rowsGroup.js") ?>" type="text/javascript"></script>
        
       
    @endif

    @if(View::hasSection('datatableExport'))
        <script src="<?= asset("template/app-assets/vendors/js/tables/buttons.html5.min.js") ?>" type="text/javascript"></script>
    @endif


   
    
    @if(View::hasSection('fileInput'))
    <script src="<?= asset("template/app-assets/vendors/js/forms/tags/form-field.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/forms/custom-file-input.js") ?>"type="text/javascript"></script>
    @endif

    @if(View::hasSection('tooltip'))
    <script src="<?= asset("template/app-assets/js/scripts/tooltip/tooltip.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('graficas'))
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartist/line/area.js") ?>" type="text/javascript"></script>
    @endif
    
    @if(View::hasSection('map'))
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{config('google.maps.api_key')}}&sensor=false&libraries=places">
        </script>
    @endif
    
    @if(View::hasSection('ckeditor'))
        <script src="<?= asset("template/app-assets/vendors/js/editors/ckeditor/ckeditor.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('colorpicker'))
        <script src="<?= asset("template/app-assets/vendors/js/pickers/colorpicker/colorpicker.min.js") ?>" type="text/javascript"></script>
    @endif

    @if(View::hasSection('charts3'))
        <script src="<?= asset("template/app-assets/vendors/js/charts/chart_3_6.min.js") ?>" type="text/javascript"></script>
    @endif
    @if(View::hasSection('charts'))
    <script src="<?= asset("template/app-assets/vendors/js/charts/chart.min.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/line/line.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/simpleheat/simpleheat.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/bar/column.js") ?>" type="text/javascript"></script>
    
    <!--<script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/bar/bar.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/bar/column-stacked.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/line/line-area.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/pie-doughnut/pie-simple.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/pie-doughnut/doughnut-simple.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/polar-radar/polar.js") ?>" type="text/javascript"></script>
    <script src="<?= asset("template/app-assets/js/scripts/charts/chartjs/polar-radar/radar.js" ) ?>" type="text/javascript"></script> -->
    

    @endif
    
    <!--- inclusión permanente del js de usuarios para editar el perfil  -->
    @if(Menu::getControllerName() != 'UserController')
        <script src="<?= url('/') ?>/assets/js/layouts/profile.js?v=<?= $v?>"></script>
    @endif
    
    @if(View::hasSection('viewjs'))
        <script src="<?= url('/') ?>/assets/js/@yield('viewjs').js?v=<?=$v?>"></script>
    @endif
    
    @if(View::hasSection('viewjs2'))
        <script src="<?= url('/') ?>/assets/js/@yield('viewjs2').js?v=<?=$v?>"></script>
    @endif

    @if(View::hasSection('viewjs3'))
        <script src="<?= url('/') ?>/assets/js/@yield('viewjs3').js?v=<?=$v?>"></script>
    @endif

    @if(View::hasSection('vistimeline'))
        <script src="<?= url('/') ?>/template/app-assets/vendors/js/timeline/vis-timeline.min.js?v=<?= $v?>"></script>
    @endif

</body>
<!-- END: Body-->
@include('layouts.sub.mdl_form')

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border navbar-shadow">
        <div class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block"><?=date('Y')?> &copy; Todos los derechos reservados <a class="text-bold-800 grey darken-2" href="http://www.palmerajunior.com" target="_blank">Palmera Junior S.A.S &middot; An Anticimex Company. &REG;</a> {{config('app.shortname')}}- v{{$v}}</span>
            <ul class="list-inline float-md-right d-block d-md-inline-blockd-none d-lg-block mb-0">
                <li class="list-inline-item">Desarrollado por <a class="my-1" href="https://softwarewebsas.com/" target="_blank"> Software Web S.A.S.</a></li>
            </ul>
        </div>
    </footer>
    <!-- END: Footer-->
</html>
