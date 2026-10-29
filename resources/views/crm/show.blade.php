@extends('layouts.main')
@section('title', $crms->name)

<!--- Valiar acciones automáticas desde el controlador -->
@if(isset($autocompleteOffice))
<script>
    AUTOCOMPLETE_OFFICE = '{{ base64_encode(json_encode($autocompleteOffice->toArray())) }}';
</script>
@endif

@if(isset($openForm))
<script>
    var callOpenForm = {{isset($openForm) ? 1 : 0}};
    var callOpenFormView = "{{isset($view)? base64_encode($view) : ''}}";
</script>
@endif

@section('viewcss', 'crm/show')
@section('viewjs', 'crm/show')
@section('datatables', 'true')
@section('select2','true')
@section('icheck','true')
@section('fileInput','true')
@section('dynamic-form', 'true')
@section('sweetAlert','true')
@section('datepicker', 'true')
@section('timeline','true')
@section('ckeditor', 'true')
@section('tagsinput', 'true')
@section('map','true')
@section('toastr', 'true')
@section('fullcalendar','true')
@section('charts','true')



@section('content')
<input type="hidden" name="id" value="{{$id}}" />
<div class="col-12 col-ms-12 col-md-12 col-lg-12 col-xl-12">
    <div class="card mt-2">
        <div class="card-content">
            @if(!isset($openForm) &&  is_null($firtOfficeCustomer) && !Auth::user()->roles->pluck('slug')->contains('customer'))
                        <div class="alert alert-warning" style="margin: 50px; padding:40px; font-size:18px;" role="alert">
                            <b>¡Configuración de cliente incompleta!</b> Este cliente aún no tiene una sucursal configurada para esta sede de Palmera Junior, esto podría generar fallos al momento de ingresar a la información de cronogramas, archivos, planos y demás. 
                            @can('crms.info.admin')<a class="btn btn-warning" style="font-size:18px" data-click="openFormOffice"> Crea una sucursal ahora</a>@endif
                        </div>
                    @endif
            <div class="row" style="margin-left:15px;margin-top:15px;">
                <div class="col-12 col-sm-2 col-md-2 col-lg-2 col-xl-2" align="right">
                    <form id="showCrmImage">
                        {{ method_field('POST') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="crm_id" value="{{$crms->id}}" />
                        <input type="file" data-input="fileImage" accept="image/*" onchange="loadImage(this)"
                            class="d-none" data-id="{{ $crms->id}}" data-avatar="saveAvatarCustomer" />
                        <div data-upload="file" data-form="showCrmImage" class="customerLogo pointer">
                            <img class="avatar md pointer" style="box-shadow: 0px 0px 20px #cecece;"
                                src="<?= url($crms->avatar ); ?>" />
                            <input type="hidden" value="<?= $crms->avatar ?? 'images/default/customer.png'; ?>"
                                name="avatar" />
                            <div class="mt-1">
                                {{-- <button type="button" class="btn btn-outline-success btn-sm round"
                                    style="position: relative;left: 10px;">Cargar imagen</button> --}}
                            </div>
                        </div>
                    </form>
                    
                </div>

                <div class="col-6 col-sm-5 col-md-2 col-lg-7 col-xl-7 customerName ">

                    <h3 class="customerNameT">{{ $crms->name ?? ''}} @if($crms->customer_national) <span class="badge badge-success" style="font-size:12px!important"> Cliente Nacional</span>@endif</h3>
                    <h5 class="customerCommercialNameT">{{ $crms->commercial_name ?? ''}} @if($crms->customer_national) /  
                    <small>Sede principal: {{ count($crms->headquarters) > 0 ? $crms->headquarters->first()->name : 'Sin definir'}}</small> @endif</h5> 
                   
                    <div class="form-group">
                        
                        <select class="select2 form-control" name="OfficesCustomer" id="OfficesCustomer" >
                            
                            <?php if (isset($firtOfficeCustomer)): ?>
                            <option value="<?= $firtOfficeCustomer->id; ?>" selected=""><?= $firtOfficeCustomer->name; ?>
                            </option>
                            <?php endif; ?> 
                        </select>
                    </div>
                    <!-- <li class="dropdown dropdown-language nav-item">
                        <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ft-map-pin" style="font-size: 25px;color: black;"></i>
                            <span class="selected-language" style="font-size: 35px;color: black;">Sucursal</span>
                            <i class='la la-chevron-circle-down' style="color: black;"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown-flag" style="width: 100%;">
                            <p class="swith-headquarter-text-info" style="color: #1f1e1e!important;"> Ingresar a otra sucursal</p>
                            <div class="arrow_box">
                                @foreach ($crms->offices as $crm)
                                <a class="dropdown-item" data-click="switchHeadquarter" data-id=""
                                    href="#">{{$crm->name}} - {{$crm->full_address}}</a>
                                    @endforeach
                            </div>
                        </div>
                    </li> -->

                </div>

                @can('crms.info.admin')
                <div class="col-6 col-sm-4 col-md-2 col-lg-2 col-xl-2 customerCrm">
                    <div class="form-group">
                        <h5>Categoría CRM</h5>
                        <select class="select2 form-control typeCrm" data-id="{{ $crms->id}}"
                            data-change="saveCategoryCustomer" name="crm_type_id">
                            <option value="">--Seleccionar--</option>
                            <?php if (isset($crms->crmCategory)): ?>
                            <option value="<?= $crms->crmCategory->id; ?>" selected=""><?= $crms->crmCategory->name; ?>
                            </option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                @endif
            </div>
            <hr>

            <div class="nav-vertical schedule_activities ">
                <ul class="nav nav-tabs nav-left">
                    <li class="nav-item">
                        <a class="nav-link active" id="baseVerticalLeft2-tab1" data-toggle="tab"
                            aria-controls="tabVerticalLeft21" href="#tabVerticalLeft21" aria-expanded="true"><i
                                class="las la-user-tie"></i> Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabContacts-tab2" data-click="openlistContact"
                            data-id="<?= $crms->id ?>" data-office="<?= $crms->id ?>" data-toggle="tab" aria-controls="tabContacts" href="#tabContacts"
                            aria-expanded="false"><i class="ft-list"></i> Contactos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabSeat-tab3" data-click="openlistSeat" data-id="<?= $crms->id ?>"
                            data-toggle="tab" aria-controls="tabSeat" href="#tabSeat" aria-expanded="false"><i
                                class="ft-navigation"></i> Sucursales</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tabFile-tab4" data-click="openlistFile" data-id="<?= $crms->id ?>"
                            data-toggle="tab"  aria-controls="tabFile" href="#tabFile" aria-expanded="false"><i
                                class="ft-file"></i> Archivos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="tabQuote-tab5" data-click="openlistQuote" data-id="<?= $crms->id ?>"
                            data-toggle="tab" aria-controls="tabQuote" href="#tabQuote" aria-expanded="false"><i
                                class="ft-file-minus"></i> Cotización</a>
                    </li>

                    @can('crms.schedule-show')
                    <li class="nav-item">
                        <a class="nav-link" id="tabSchedule-tab7" data-click="openlistSchedule"
                            data-id="<?= $crms->id ?>" data-toggle="tab" aria-controls="tabSchedule" href="#tabSchedule"
                            aria-expanded="false"><i class="ft-calendar"></i> Cronograma de <br> Actividades</a>
                    </li>
                    @endcan

                    @can('crms.plans')
                        <li class="nav-item">
                            <a class="nav-link" id="tabPlanTab" data-click="openlistPlan" data-id="<?= $crms->id ?>"
                                data-toggle="tab" aria-controls="tabPlan" href="#tabPlan" aria-expanded="false"><i
                                    class="la las la-pencil-ruler la-3x"></i> Planos </a>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link" id="tabMechanismTab" data-click="openlistMechanism" data-id="<?= $crms->id ?>"
                            data-toggle="tab" aria-controls="tabMechanism" href="#tabMechanism" aria-expanded="false"><i
                                class="la las la-project-diagram la-3x"></i> Mecanismos Instalados </a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" id="tabUser-tab10" data-click="openlistUser" data-id="<?= $crms->id ?>"
                            data-toggle="tab" aria-controls="tabSeat" href="#tabUser" aria-expanded="false">
                            <i class="las la-user-circle"></i> Personal Asignado
                        </a>
                    </li> 
                    @can('crms.info.admin')
                        <li class="nav-item">
                            <a class="nav-link" id="tabTechnical-tab4" data-click="openlistTechnical" data-id="<?= $crms->id ?>"
                                data-toggle="tab" aria-controls="tabTechnical" href="#tabTechnical" aria-expanded="false"><i
                                    class="las la-user"></i> Técnicos X Servicio</a>
                        </li>
                    @endif

                    <!-- <li class="nav-item">
                        <a class="nav-link" id="tabBilling-tab8" data-click="openlistBilling" data-id="<?= $crms->id ?>" data-toggle="tab"
                            aria-controls="tabBilling" href="#tabBilling" aria-expanded="false"><i
                                class="ft-file-plus"></i> Facturación</a>
                    </li> -->
                    @can('crms.info.admin')
                    <!--<li class="nav-item">
                        <a class="nav-link" id="tabConfigBilling-tab9" data-click="openlistConfigBilling"
                            data-id="<?= $crms->id ?>" data-toggle="tab" aria-controls="tabConfigBilling"
                            href="#tabConfigBilling" aria-expanded="false"><i class="ft-settings"></i>Config
                            Facturación</a>
                    </li> -->
                    @endcan
                    @can('crms.info.admin')
                    <li class="nav-item">
                        <a class="nav-link" id="tabProcess-tab10" data-click="openlistProcess"
                            data-id="<?= $crms->id ?>" data-toggle="tab" aria-controls="tabProcess" href="#tabProcess"
                            aria-expanded="false"><i class="ft-layers"></i> Gestión</a>
                    </li>
                    @endcan

                    <!--- Habilitar solo al cliente de pruebas -->
                   
                        <li class="nav-item">
                            <a class="nav-link" id="tabProcess-tab11" data-click="openlistReport" data-id="<?= $crms->id ?>"
                                data-toggle="tab" aria-controls="tabReport" href="#tabReport" aria-expanded="false"><i
                                    class="ft-pie-chart"></i> Informes</a>
                        </li>
                   
                    @can('ticket.index.customer')
                        <li class="nav-item">
                            <a class="nav-link" id="tabProcess-tab12" data-click="openlistTicket" data-id="<?= $crms->id ?>"
                                data-toggle="tab" aria-controls="tabTicket" href="#tabTicket" aria-expanded="false"><i
                                    class="ft-server"></i> Tickets</a>
                        </li>
                   @endcan
                    @can('quotes.diagnosis')
                        <li class="nav-item">
                            <a class="nav-link" id="tabProcess-tab13" data-click="openlistDiagnostic" data-id="<?= $crms->id ?>"
                                data-toggle="tab" aria-controls="tabDiagnostic" href="#tabDiagnostic" aria-expanded="false"><i
                                    class="ft-activity"></i>Diagnosticos</a>
                        </li>
                   @endcan
                </ul>
                <div class="tab-content px-1 ver">
                    <div role="tabpanel" class="tab-pane active" id="tabVerticalLeft21" aria-expanded="true"
                        aria-labelledby="baseVerticalLeft2-tab1">
                        @can('crms.info.edit')

                        <div style="z-index:1000!important;" align="right">
                            <a href="#" class="btn btn-outline-teal" data-click="openFormCustomer"
                                data-id="<?= $crms->id ?>">
                                <i class="ft-plus"></i>
                                Editar Cliente
                            </a>
                        </div>

                        @endcan

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="list" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabContacts" aria-labelledby="tabContacts-tab2">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            @can('crms.info.admin')
                            <div class="form-group">
                                <a href="" style="z-index:1000!important;"
                                    class="btn btn-outline-teal" data-click="openFormContact"
                                    data-id>
                                    <i class="ft-plus"></i>&nbsp;Agregar contacto
                                </a>
                         
                                @if(isset($crm))
                                <a href="#" style="z-index:1000!important;"
                                    data-click="openFormUser" data-id="{{$crms->id}}" data-office="{{ $crms->id }}"
                                    class="btn btn-outline-warning" >
                                    <i class="ft-plus"></i>&nbsp;Usuario de otro cliente
                                </a>
                                @endif
                                @new(['date' => '2022-04-05', 'title' => 'Usuario de otro cliente', 'text' => 'Permite asociar un contacto de otro cliente (con acceso al software), esto sirve para que un usuario externo (ejemplo: un inspector) pueda administrar múltiples clientes'])
                            </div>
       
                            @endcan
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvance" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabSeat" aria-labelledby="tabSeat-tab3">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            @can('crms.info.admin')
                            <div class="form-group">
                                <a href="" style="z-index:1000!important;"
                                    class="btn btn-outline-teal buttonAdd_contactCrm" data-click="openFormOffice"
                                    data-id>
                                    <i class="ft-plus"></i>&nbsp;Agregar Sucursales
                                </a>
                            </div>
                            @endcan
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvanceOffice" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabUser" aria-labelledby="tabSeat-10">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            @can('crms.info.admin')
                                <div class="form-group">
                                    <a href="#" style="z-index:1000!important;" class="btn btn-outline-teal buttonAdd_userCrm" data-click="openFormUser" data-id="{{$crms->id}}">
                                        <i class="ft-plus"></i>&nbsp;Asignar Nuevo
                                    </a>
                                </div>
                            @endcan

                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvanceUser" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabFile" aria-labelledby="tabFile-tab4">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group">
                                <a href="" style="z-index:1000!important;"
                                    class="btn btn-outline-teal buttonAdd_contactCrm" data-click="openFormFile" data-id>
                                    <i class="ft-plus"></i>&nbsp;Agregar Documento
                                </a>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvanceFIle" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('crms.info.admin')
                    <div class="tab-pane" id="tabTechnical" aria-labelledby="tabTechnical-tab4">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group">
                                <a href="" style="z-index:1000!important;"
                                    class="btn btn-outline-teal buttonAdd_contactCrm" data-click="openFormTechnical"
                                    data-id>
                                    <i class="ft-plus"></i>&nbsp;Agregar Parámetro
                                </a>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvanceTechnical" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    <div class="tab-pane" id="tabQuote" aria-labelledby="tabQuote-tab5">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvanceQuote" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabPlan" aria-labelledby="tabPlan">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvancePlan" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabMechanism" aria-labelledby="tabMechanism">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvanceMechanism" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tabSchedule" aria-labelledby="tabSchedule-tab7">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <!--- incluir filtros para consulta de cronograma -->
                            <section class="row">
                                
                                @include('crm.forms.frames.schedule_filters')
                                @include('crm.sub.calendarSchedule')
                            </section>

                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listAdvanceSchedule" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
  
                  @can('crms.info.admin')

                        <!--<div class="tab-pane" id="tabConfigBilling" aria-labelledby="tabConfigBilling-tab9">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div style="z-index:1000!important;" align="right">
                                    <a href="{{asset('images/default/formato_descarga_reporte_ruta.pdf')}}" class="btn btn-outline-teal" data-click="openFormConfigBilling"
                                        data-id="<? $crms->id ?>">
                                        <i class="ft-plus"></i>
                                        Editar Configuracion
                                    </a>
                                </div>


                                <div class="row fixRow">
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div id="listConfigBilling" class="relative" style="min-height: 380px;">
                                            @include('layouts.loading.full')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    @endcan 
                    @can('crms.info.admin')
                    <div class="tab-pane" id="tabProcess" aria-labelledby="tabProcess-tab10">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <section class="row" id="filteContainer">
                                @include('layouts.loading.full')
                            </section>
                            <div class="form-group">
                                <a href="" style="z-index:1000!important;" class="btn btn-outline-teal"
                                    data-click="openFormEvent" data-id>
                                    <i class="ft-plus"></i>&nbsp;Agregar evento
                                </a>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="listPrecess" class="relative" style="min-height: 380px;">
                                    @include('layouts.loading.full')
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan
                    <div class="tab-pane" id="tabTicket" aria-labelledby="tabTicket-tab12">
                        <div id="listTickets" class="relative" style="min-height: 500px;">
                            @include('layouts.loading.full')
                        </div>
                        
                    </div>
                    <div class="tab-pane" id="tabDiagnostic" aria-labelledby="tabDiagnostic-tab12">
                        <div id="listDiagnostic" class="relative" style="min-height: 500px;">
                            @include('layouts.loading.full')
                        </div>
                        
                    </div>
                    <div class="tab-pane" id="tabReport" aria-labelledby="tabReport-tab11">
                        @include('crm.reports.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--- Incluir modales del módulo -->
@include('crm.sub.mdl_formShow')
@include('crm.sub.mdl_formShowFullScreen')
@include('crm.sub.mdl_form')
@include('crm.sub.mdl_delete')

@include('crm.sub.mdl_formShowDelete')

@include('poll.sub.mdl_showResults')
@endsection