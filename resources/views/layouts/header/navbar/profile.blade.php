<li class="dropdown dropdown-user nav-item">
    <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"> <span class="avatar avatar-online">
            <img class="avatar xxs mr-1" src="<?=Auth::user()->full_avatar?>" alt="avatar" onerror="this.src='{{url("images/default/user.jpg")}}'"><i></i></span></a>
    <div class="dropdown-menu dropdown-menu-right">
        
        <div class="arrow_box_right">
            <a class="dropdown-item" href="#">
                <span class="avatar avatar-online">
                    <img src="<?=Auth::user()->full_avatar?>" alt="avatar" onerror="this.src='{{url("images/default/user.jpg")}}'">
                    <span class="user-name text-bold-700 ml-1">{{Auth::user()->name}}</span>
                    <span class="profile-roles">
                            <span class="user-name text-bold-700 ml-1">{{Auth::user()->roles->pluck('name')->implode(', ')}}</span>  
                        </span>
                </span>
            </a>
            <div class="dropdown-divider"></div><a class="dropdown-item" href="#" data-id="{{Auth::user()->id}}" data-click="openFormProfile"><i class="ft-user"></i> Editar Perfil</a>
            <div class="dropdown-divider"></div><a class="dropdown-item" href="{{route('logout')}}"><i class="ft-power"></i> Salir</a>
        </div>
    </div>
</li>