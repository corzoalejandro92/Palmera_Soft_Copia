<li class="scrollable-container media-list w-100 {{isset($notification->read_at) ? '':'notification-unread'}}">
    <a href="{{ route('crm.expiredNotify', [$notification->id]) }}" >
        <div class="media">
            <div class="media-left align-self-center">
                <img  class="icon-bg-circle" src="{{isset($notification->data['crm']['avatar']) ? $notification->data['crm']['avatar'] : url("images/default/office.png")}}" onerror="this.src='{{url("images/default/office.png")}}'" />
                <i class="las la-exclamation-triangle action-icon-notification-danger"></i>
                {{-- <i class="ft-plus-square icon-bg-circle bg-cyan"></i> --}}
            </div>
            <div class="media-body">
                @if(!isset($notification->read_at))
                    <div class="unread-mark"></div>
                @endif
                <h5 class="media-heading">{{ count($notification->data['offices']) }} sucursales de {{$notification->data['headquarter']['name']}} sin cronograma para ejecutar </h5>
                <p class="notification-text font-small-3 text-muted">Hemos encontrado sucursales de clientes que han completado su cronograma de actividades y a partir de ahora no tienen actividades pendientes por ejecutar, haz clic para ver la lista</p><small>
                    <time class="media-meta text-muted" >{{\Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans()}}</time></small>
            </div>
        </div>
    </a>
</li>
