<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="Iniciar Sesión | Sistema administrativo Palmera Junior S.A.S.">
    <meta name="keywords" content="Palmera Junior">
    <meta name="author" content="ThemeSelect">
    <title>PJS | @yield('title', 'Login')</title>
    <link rel="apple-touch-icon" href="<?=url("template/app-assets/images/logo/logo-min.png")?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?=url("template/app-assets/images/logo/logo-min.png")?>">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;1,300;1,400&display=swap" rel="stylesheet">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/bootstrap-extended.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/colors.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/components.css")?>">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/core/menu/menu-types/vertical-menu-modern.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/core/colors/palette-gradient.css")?>">
    <link rel="stylesheet" type="text/css" href="<?=url("template/app-assets/css/pages/login-register.css")?>">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?=url("template/assets/css/style.css")?>">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-color="bg-gradient-x-purple-red" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>" type="text/javascript"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?=url("template/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js")?>" type="text/javascript"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?=url("template/app-assets/js/core/app-menu.js")?>" type="text/javascript"></script>
    <script src="<?=url("template/app-assets/js/core/app.js")?>" type="text/javascript"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?=url("template/app-assets/js/scripts/forms/form-login-register.js")?>" type="text/javascript"></script>
    <!-- END: Page JS-->

</body>
<!-- END: Body-->

</html>