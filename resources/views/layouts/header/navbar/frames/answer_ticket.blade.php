<li class="scrollable-container media-list w-100 {{isset($notification->read_at) ? '':'notification-unread'}}">
    <a href="{{auth()->user()->roles->pluck('slug')->contains('customer') ? '#' : route('tickets.tickets')}}" data-click="deleteNotification" data-id="{{$notification->id}}">
        <div class="media">
            <div class="media-left align-self-center">
                <img  class="icon-bg-circle" src="{{isset($notification->data['crm']['avatar']) ? $notification->data['crm']['avatar'] : url("images/default/customer.png")}}" onerror="this.src='{{url("images/default/customer.png")}}'" />
                <i class="las la-ticket-alt action-icon-notification-orange"></i>
            </div>
            <div class="media-body">
                @if(!isset($notification->read_at))
                    <div class="unread-mark"></div>
                @endif
                <h6 class="media-heading">Nueva respuesta del  ticket # {{$notification->data['ticket']['id']}}</h6>
                <p class="notnew_ticketification-text font-small-3 text-muted">Nueva respuesta de {{$notification->data['crm']['name']}} en la sucursal {{$notification->data['office']['name']}} por {{$notification->data['registered']['name']}}</p></p><small>
                    <time class="media-meta text-muted" >{{\Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans()}}</time></small>
            </div>
        </div>
    </a>
</li>
