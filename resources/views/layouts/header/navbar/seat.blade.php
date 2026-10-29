<?php
    use App\Headquarter;
    
?>

@if(Auth::user()->roles->pluck('slug')->contains('customer'))
    <li class="dropdown dropdown-language nav-item">
        <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="la la-building"></i>
            <span class="selected-language">Cliente {{session('crm_name')}}</span>
            <i class='la la-chevron-circle-down'></i>
        </a>
            <?php 
                $crms = Auth::user()->crms()->where('crms.id','!=',session('crm_id'))->get();
            ?>
            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                <p class="swith-headquarter-text-info"> Ingresar a otro cliente</p>
                <div class="arrow_box">
                    @forelse($crms as $key => $crm)
                        <a class="dropdown-item" data-click="switchCrm" data-id="{{$crm->id}}" href="#">{{$crm->name}}</a>
                    @empty
                    <a class="dropdown-item"  href="#">
                        <div align='center'>
                            <i style='font-size: 12px; color:#737070;'>No hay clientes disponibles para ingresar.</i> 
                        </div>
                    </a>
                    @endforelse
                </div>      
            </div>
    </li>
@endif

<li class="dropdown dropdown-language nav-item">
    <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="las la-city" style="font-size: 21px;"></i>
        <span class="selected-language">Sede {{session('headquarter_name')}}</span>
        @if(Auth::user()->roles->pluck('slug')->contains('root'))
            <i class='la la-chevron-circle-down'></i>
        @endif
    </a>
    @if(Auth::user()->roles->pluck('slug')->contains('root'))
        <?php 
            $headquarters = Headquarter::where('id','!=',session('headquarter_id'))->get();
        ?>
        <div class="dropdown-menu" aria-labelledby="dropdown-flag">
            <p class="swith-headquarter-text-info"> Ingresar a otra sede</p>
            <div class="arrow_box">
                @forelse($headquarters as $key => $headquarter)
                <a class="dropdown-item" data-click="switchHeadquarter" data-id="{{$headquarter->id}}" href="#">{{$headquarter->name}}</a>
                @empty
                <a class="dropdown-item"  href="#">
                    <div align='center'>
                        <i style='font-size: 12px; color:#737070;'>No hay sedes disponibles para ingresar.</i> 
                        @can('headquarters.index')
                        <a class='btn btn-teal' href='{{route('headquarters.index')}}'>Crear nueva</a>
                        @endcan
                    </div>
                </a>
                @endforelse
            </div>      
        </div>
    @endif
</li>
