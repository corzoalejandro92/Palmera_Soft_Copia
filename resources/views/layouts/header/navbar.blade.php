<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="collapse navbar-collapse show" id="navbar-mobile">

                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                    </ul>

                    {{-- <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                        <li class="nav-item dropdown navbar-search">
                            <a class="nav-link dropdown-toggle hide" data-toggle="dropdown" href="#">
                                @yield('title')
                            </a>
                        </li>
                    </ul> --}}
                    <ul class="nav navbar-nav float-right">
                        <!-- Sede del sistema -->
                            @include('layouts.header.navbar.seat')
                        <!-- Notificaciones del sistema -->
                        <div class="notification-container">
                            @include('layouts.header.navbar.notifications')
                        </div>
                            
                        <!-- End notificaciones del sistema -->
                        <!--profile -->
                            @include('layouts.header.navbar.profile')
                        <!-- end profile -->
                    </ul>
                </div>
            </div>
        </div>
    </nav>