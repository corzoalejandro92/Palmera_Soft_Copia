@php
    $count = auth()
        ->user()
        ->unreadNotifications()
        ->count();
    $total_notifications_count = auth()->user()->notifications()->count();
@endphp
<li class="dropdown dropdown-notification nav-item">
    <a class="nav-link nav-link-label markAsRead" data-toggle="dropdown" href="#"><i
            class="ficon ft-bell {{ $count > 0 ? 'bell-shake' : '' }}" id="notification-navbar-link"></i>
        <span class="badge badge-pill badge-sm badge-danger badge-up badge-glow badge-notification"
            style="{{ $count > 0 ? '' : 'display:none;' }}">{{ $count }}</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
        <div class="arrow_box_right">
            <li class="dropdown-menu-header">
                <h6 class="dropdown-header"><span class="grey darken-2">Notificaciones
                        ({{ $total_notifications_count }})</span></h6>
            </li>
            <div style="overflow-y: auto;max-height: 80vh;">
                @forelse (auth()->user()->notifications()->limit(10)->get() as  $notification)
                    @include('layouts.header.navbar.frames.' . snake_case(class_basename($notification->type)))
                @empty
                    <br>No hay notificaciones para mostrar.
                @endforelse
            </div>
            <li class="dropdown-menu-footer">
                <a class="{{ $total_notifications_count > 5? 'btn btn-danger-outline  p-1': '' }} dropdown-item text-muted text-center all-notifications"
                    type="button" href="javascript:void(0)">
                    @if ($total_notifications_count > 5)
                        Eliminar todas las notificaciones
                    @endif
                </a>
            </li>
        </div>
    </ul>
</li>
