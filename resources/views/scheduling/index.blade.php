

@extends('layouts.main')

@section('title', 'Agenda de trabajo')
@section('fullcalendar','true')
@section('select2','true')
@section('sweetAlert','true')
@section('contextMenus ','true')
@section('tagsinput','true')
@section('datepicker', 'true')
@section('timepicker','true')
@section('map','true')

@section('viewjs2', 'scheduling/common')
@section('viewcss','scheduling/index')
@section('viewjs','scheduling/index')


@section('content')

<!-- filtros de calendario -->
<!-- end filtros de calendario -->

<section class="row" id="filteContainer">
      @include('layouts.loading.full')  
</section>

<!--    Contenderor de calendario-->
<section id="agendaSection" class="row">
    @include('layouts.loading.full')
    @include('scheduling.sub.agenda')

</section>

<!--- Incluir modales del módulo -->
@include('scheduling.sub.mdl_form')
@include('scheduling.sub.mdl_delete')
<!--    Fin contenedor de calendario -->    

<script>
    var callOpenForm        = {{isset($openForm) ? 1:0}};
    var callOpenFormView    = "{{isset($view)? base64_encode($view) : ''}}";
</script>

@endsection
