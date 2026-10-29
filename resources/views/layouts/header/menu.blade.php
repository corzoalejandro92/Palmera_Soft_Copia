<?php
use App\General\Menu;
$foxcon_integration = \App\Integration::find(\App\Integration::FOXCON);
?>

<div class="main-menu menu-fixed menu-{{ config('app.menu_opacity') }} menu-accordion menu-shadow"
    data-scroll-to-active="true"
    data-img="<?= url('template/app-assets/images/backgrounds/' . config('app.menu_background') . '.jpg') ?>">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row position-relative">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="/">
                    @if (config('app.showicon'))
                        <img class="brand-logo" src="<?= url('template/app-assets/images/logo/logo-min.png') ?>"
                            alt="PJS logo" />
                    @endif
                    <h3 class="brand-text">{{ config('app.shortname') }}</h3>
                </a>
            </li>
            <li class="nav-item d-none d-md-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0"
                    data-toggle="collapse"><i class="toggle-icon ft-disc font-medium-3" data-ticon="ft-disc"></i></a>
            </li>
            <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
    </div>
    <div class="navigation-background"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            @can('dashboards.index')
                <li class=" nav-item {{ Menu::isActAction('Home', 'index') }}"><a href="{{ route('home') }}"><i
                            class="ft-home"></i><span class="menu-title" data-i18n="">Indicadores</span></a>
                </li>
            @endcan

            @can('scheduling.index')
                <li class=" nav-item {{ Menu::isActAction('scheduling', 'index') }}">
                    <a href="{{ route('scheduling.index') }}"><i class="ft-calendar"></i><span class="menu-title"
                            data-i18n="">Agenda</span></a>
                </li>
            @endcan

            @canAny(['scheduling.automation', 'routes.index'])
                <li class=" nav-item {{ Menu::isController('scheduling') && !Menu::isActAction('scheduling', 'index') }}">
                    <a href="#">
                        <i class="ft-calendar"></i>
                        <span class="menu-title" data-i18n="">Programación</span>
                    </a>
                    <ul class="menu-content">
                        @can('scheduling.automation')
                            <li class="{{ Menu::isActAction('scheduling', 'callsheet') }}">
                                <a class="menu-item" href="{{ route('scheduling.callsheet') }}">Hoja de llamadas</a>
                            </li>
                        @endcan
                        @can('scheduling.automation')
                            <li class="{{ Menu::isActAction('scheduling', 'automation') }}">
                                <a class="menu-item" href="{{ route('scheduling.automation') }}">Automatización</a>
                            </li>
                        @endcan
                        @can('routes.index')
                            <li class="{{ Menu::isActAction('scheduling', 'routes') }}">
                                <a class="menu-item" href="{{ route('scheduling.routes') }}">Rutas</a>
                            </li>
                        @endcan
                        @can('routes.index')
                            <li class="{{ Menu::isActAction('scheduling', 'execution') }}">
                                <a class="menu-item" href="{{ route('scheduling.execution') }}">Ejecución</a>
                            </li>
                        @endcan
                        @can('routes.index')
                            <li class="{{ Menu::isActAction('scheduling', 'programmer') }}">
                                <a class="menu-item" href="{{ route('scheduling.programmer') }}">Programador</a>
                            </li>
                        @endcan
                        @if (Auth::user()->roles->pluck('slug')->contains('root') ||
                                Auth::user()->roles->pluck('slug')->contains('admin') ||
                                Auth::user()->roles->pluck('slug')->contains('boss_programming') ||
                                Auth::user()->roles->pluck('slug')->contains('programming'))
                            <li class="{{ Menu::isActAction('scheduling', 'hours') }}">
                                <a class="menu-item" href="{{ route('scheduling.hours') }}">Horas extras
                                    @new(['title' => 'Liquidación de horas extras', 'date' => '27-04-2022'])</a>
                            </li>
                        @endif
                        {{-- @can('routes.index')
                        <li class="{{Menu::isActAction('scheduling', 'reports')}}">
                            <a class="menu-item" href="{{route('scheduling.routes')}}">Indicadores</a>
                        </li>
                    @endcan --}}
                    </ul>
                </li>
            @endcanAny

            @can('crms.index')
                <li class=" nav-item {{ Menu::isActAction('crm', 'index') }}"><a href="{{ route('crms.index') }}"><i
                            class="la la-building"></i><span class="menu-title" data-i18n="">CRM</span></a>
                </li>
            @endcan

            @if (Auth::user()->roles->pluck('slug')->contains('customer'))
                @php
                    $crm = Auth::user()
                        ->crms()
                        ->first()->id;
                @endphp
                @can('crms.show')
                    <li class=" nav-item"><a href="{{ route('crms.show', ['crm' => $crm]) }}"><i
                                class="la la-building"></i><span class="menu-title" data-i18n="">Mi información</span></a>
                    </li>
                @endcan
            @endif

            @can('quotes.index')
                <li class="nav-item {{ Menu::isController('quote') }}"><a href="#"><i class="ft-file-text"></i><span
                            class="menu-title" data-i18n="">Cotizaciones</span></a>
                    <ul class="menu-content">
                        @can('quotes.create')
                            <li class="{{ Menu::isActAction('Quote', 'create') }}"><a class="menu-item"
                                    href="{{ route('quotes.create') }}">Crear</a></li>
                        @endcan
                        <li class="{{ Menu::isActAction('Quote', 'index') }}"><a class="menu-item"
                                href="{{ route('quotes.index') }}">Consultar</a></li>
                        @canAny(['indicator.report.month.index', 'indicator.report.year.index'])
                            <li class=" nav-item {{ Menu::isActAction('indicatorReport', 'indicatorsMonth') }}">
                                <a href="{{ route('indicator.month.indicators') }}">@new(['date' => '2021-11-10'])<span class="menu-title"
                                        data-i18n="">Indicadores</span></a>
                            </li>
                        @endcanAny
                    </ul>
                </li>
            @endcan

            @canAny(['inventory.index', 'OrderPurchases.index', 'confirmationTransfer.index'])

                <li class=" nav-item {{ Menu::isController('inventory') }}">
                    <a href=""><i class="la la-archive"></i><span class="menu-title"
                            data-i18n="">Inventario</span></a>
                    <ul class="menu-content">
                        @can('inventory.index')
                            <li class="{{ Menu::isActAction('inventory', 'index') }}"><a class="menu-item"
                                    href="{{ route('inventory.index') }}">Inventario</a></li>


                            @if (
                                $foxcon_integration->enabled &&
                                    in_array(\App\Headquarter::find(session('headquarter_id'))->id, $foxcon_integration->headquarters))
                                <li class="{{ Menu::isActAction('inventory', 'foxconDocs') }}"><a class="menu-item"
                                        href="{{ route('inventory.foxcon') }}">Movimientos</a></li>

                                @if (Auth::user()->roles->pluck('slug')->contains('root'))
                                    <li>
                                        <a class="menu-item" href="{{ route('importFoxcon.index') }}" style="color: #8b6360;">
                                            Importar saldos</a>
                                    </li>
                                @endif
                            @endif

                            @if (
                                $foxcon_integration->enabled &&
                                    ($foxcon_integration->enabled && in_array('99999999', $foxcon_integration->headquarters)))
                                <ul class="menu-content">
                                    <li
                                        class="has-sub {{ Menu::isController('importFoxcon') && Menu::isActAction('importFoxcon', 'bnacional') }}">
                                        <a class="menu-item" href="#" style="color: #607D8B;">Bodega Nacional</a>
                                        <ul class="menu-content" style="">
                                            <li><a class="menu-item" href="{{ route('inventory.foxcon') }}?bnacional=true"
                                                    style="color: #607D8B;">Movimientos (BN)</a></li>

                                            @if (Auth::user()->roles->pluck('slug')->contains('root'))
                                                <li>
                                                    <a class="menu-item" href="{{ route('importFoxcon.bnacional') }}"
                                                        style="color:  #8b6360;">
                                                        Importar saldos (BN)</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            @endif
                        @endcan


                        @can('OrderPurchases.index')
                            <li><a class="menu-item" href="{{ route('orderPurchases.index') }}">Orden de compra</a>
                            </li>
                        @endcan
                        @can('OrderPurchases.index')
                            <li><a class="menu-item" href="{{ route('orderPurchases.index-general') }}">Consolidado
                                    de<br>ordenes
                                    de compra</a>
                            </li>
                        @endcan
                        @can('confirmationTransfer.index')
                            <li><a class="menu-item" href="{{ route('inventory.technical') }}">Mi inventario</a>
                            </li>
                        @endcan
                        @can('confirmationTransfer.index')
                            <li><a class="menu-item" href="{{ route('inventory.indexTranferInventory') }}">Confirmación de
                                    <br>
                                    transferencia</a>
                            </li>
                        @endcan


                    </ul>
                </li>
            @endcanAny

            @can('billings.index')
                <li class=" nav-item"><a href=""><i class="la la-newspaper-o"></i><span class="menu-title"
                            data-i18n="">Facturación</span></a>
                    <ul class="menu-content">
                        {{-- @can('billings.create')
                    <li><a class="menu-item" href="{{route('billings.create')}}">Generar proforma</a></li>
                    @endcan
                    @can('billings.index')
                    <li><a class="menu-item" href="{{route('billings.index')}}">Lista proforma</a>
                    </li>
                    @endcan --}}
                        @can('billings.index')
                            <li><a class="menu-item" href="{{ route('billings.evsf') }}">Notas de factura</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @canAny(['headquarters.index', 'infestations.index', 'crms.areas-index', 'services.index', 'products.index',
                'availability.index', 'users.index', 'users.index', 'vehicles.index', 'billings.setting', 'settings.index',
                'polls.index', 'quote.config'])
                <li class="nav-item"><a href="#"><i class="ft-aperture"></i><span class="menu-title"
                            data-i18n="">Configuraciones</span></a>
                    <ul class="menu-content">
                        @if (Auth::user()->roles->pluck('slug')->contains('root'))
                            <li><a class="menu-item" href="{{ route('integrations') }}">Integraciones</a>
                            </li>
                        @endif
                        @can('headquarters.index')
                            <li><a class="menu-item" href="{{ route('headquarters.index') }}">Sedes</a>
                            </li>
                        @endcan
                        @can('headquarters.self-edit')
                            <li><a class="menu-item" href="{{ route('headquarters.index') }}">Datos de la sede</a>
                            </li>
                        @endcan

                        @can('infestations.index')
                            <li>
                                <a class="menu-item" href="{{ route('infestations.index') }}">Controles</a>
                            </li>
                        @endcan

                        @can('crms.areas-index')
                            <li>
                                <a class="menu-item" href="{{ route('crms.areas-index') }}">Areas de clientes</a>
                            </li>
                        @endcan

                        @can('services.index')
                            <li><a class="menu-item" href="{{ route('services.index') }}">Servicios</a>
                            </li>
                        @endcan


                        @can('products.index')
                            <li><a class="menu-item" href="{{ route('products.index') }}">Productos</a>
                            </li>

                            <li><a class="menu-item" href="{{ route('convection.index') }}">Convenciones</a>
                            </li>
                        @endcan

                        @can('applications.index')
                            <li><a class="menu-item" href="{{ route('applications.index') }}">Aplicaciones</a>
                            </li>
                        @endcan

                        @can('doses.index')
                            <li><a class="menu-item" href="{{ route('doses.index') }}">Dosis</a>
                            </li>
                        @endcan

                        @can('units.index')
                            <li><a class="menu-item" href="{{ route('units.index') }}">Unidad de Medida</a>
                            </li>
                        @endcan

                        @can('unit-doses.index')
                            <li><a class="menu-item" href="{{ route('unitDoses.index') }}">Unidad de Dosis</a>
                            </li>
                        @endcan

                        @can('quotes.config')
                            <li>
                                <a class="menu-item" href="{{ route('quotes.config') }}">Formatos Cotización</a>
                            </li>
                        @endcan

                        @can('availability.index')
                            <li>
                                <a class="menu-item" href="{{ route('availability.index') }}">Disponibilidades</a>
                            </li>
                        @endcan

                        @can('users.index')
                            <li>
                                <a class="menu-item" href="{{ route('users.index') }}">Usuarios</a>
                            </li>
                        @endcan

                        @can('vehicles.index')
                            <li>
                                <a class="menu-item" href="{{ route('vehicles.index') }}">Vehículos</a>
                            </li>
                        @endcan

                        @can('peakPlate.index')
                            <li>
                                <a class="menu-item" href="{{ route('peak-plate.index') }}">Pico y Placa
                                    @new(['date' => '2022-02-25'])</a>
                            </li>
                        @endcan

                        <!-- @can('billings.create')
        <li><a class="menu-item" href="{{ route('billings.setting') }}">Facturación</a>
                                </li>
    @endcan -->

                        @can('settings.index')
                            <li><a class="menu-item" href="{{ route('settings.index') }}">Consecutivos</a>
                            </li>
                        @endcan

                        @can('documentTypes.index')
                            <li><a class="menu-item" href="{{ route('documentTypes.index') }}">Tipos de Documentos</a>
                            </li>
                        @endcan

                        @can('objetive.index')
                            <li><a class="menu-item" href="{{ route('objetives.index') }}">@new(['date' => '2021-11-10'])Metas</a>
                            </li>
                        @endcan

                        @if (Auth::user()->can('polls.index') || Auth::user()->can('polls.survey.answer.index'))
                            <li class="nav-item"><a href="#"><i class="ft-file-text"></i><span class="menu-title"
                                        data-i18n="">Encuestas</span></a>
                                <ul class="menu-content">
                                    @can('polls.index')
                                        <li><a class="menu-item"href="{{ route('polls.create') }}">Crear</a></li>
                                        <li><a class="menu-item"href="{{ route('polls.index') }}">Consultar</a></li>
                                    @endcan
                                    @can('polls.survey.answer.index')
                                        <li><a class="menu-item"href="{{ route('polls.list.resultSurvey') }}">Respuestas
                                                @new(['date' => '2022-03-30'])</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endif



                        {{-- <li><a class="menu-item" href="#">Rol</a>
                                <ul class="menu-content">
                                    <li><a class="menu-item" href="">Crear Rol</a>
                                    </li>
                                </ul>
                                <ul class="menu-content">
                                    <li><a class="menu-item" href="">Lista de Rol</a>
                                    </li>
                                </ul>
                            </li> --}}
                    </ul>

                </li>
            @endcanAny
            @if (Auth::user()->roles->pluck('slug')->contains('root'))
                <li class=" nav-item {{ Menu::isActAction('import', 'index') }}"><a
                        href="{{ route('import.index') }}"><i class="la la-upload"></i><span class="menu-title"
                            data-i18n="">Importar Data</span></a>
                </li>
            @endif
            @if (Auth::user()->roles->pluck('slug')->contains('root'))
                <li class=" nav-item {{ Menu::isActAction('audit', 'audits') }}">
                    <a href="{{ route('audit.audits') }}"><i class="lab la-amilia"></i>@new(['date' => '2021-10-30'])<span
                            class="menu-title" data-i18n="">Auditorias</span></a>
                </li>
            @else
                @can('audit.index')
                    <li class=" nav-item {{ Menu::isActAction('audit', 'audits') }}">
                        <a href="{{ route('audit.audits') }}"><i class="lab la-amilia"></i>@new(['date' => '2021-10-30'])<span
                                class="menu-title" data-i18n="">Auditorias</span></a>
                    </li>
                @endcan
            @endif
            @can('ticket.index')
                <li class=" nav-item {{ Menu::isActAction('ticket', 'tickets') }}">
                    <a href="{{ route('tickets.tickets') }}"><i class="las la-ticket-alt"></i>@new(['date' => '2022-01-10'])<span
                            class="menu-title" data-i18n="">Tickets</span></a>
                </li>
            @endcan

            @if (Auth::user()->can('polls.index') || Auth::user()->can('polls.survey.answer.index'))
                <li class="nav-item"><a href="#"><i class="ft-file-text"></i><span class="menu-title"
                            data-i18n="">Encuestas</span> @new(['date' => '2022-04-04'])</a>
                    <ul class="menu-content">
                        @can('polls.index')
                            <li><a class="menu-item"href="{{ route('polls.create') }}">Crear</a></li>
                            <li><a class="menu-item"href="{{ route('polls.index') }}">Consultar</a></li>
                        @endcan
                        @can('polls.survey.answer.index')
                            <li><a class="menu-item"href="{{ route('polls.list.resultSurvey') }}">Respuestas
                                    @new(['date' => '2022-03-30'])</a></li>
                        @endcan
                    </ul>
                </li>
            @endif


        </ul>
    </div>
</div>
