
<!DOCTYPE html>
<html class="loading" lang="es" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <script src="<?=url("template/app-assets/vendors/js/vendors.min.js")?>"> type="text/javascript"></script>
    <script src="<?=url("assets/js/crm/reports/_apiHeatMap.js")?>" type="text/javascript"> </script>
</head>
<body>

<div id="wrapper">
    <p>Hola mundo</p>
    <section id="sectionCanvas"></section>
</div>    
</body>
<script>
    var branchOffice = '{{ $office }}';
    var _data = '{{json_encode($data) }}';
    var area = '{{json_encode($area) }}';
</script>
</html>



