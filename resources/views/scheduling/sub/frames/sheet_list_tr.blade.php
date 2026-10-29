<tr class="pointer" data-click="{{ $schedule->id }}">
    <td></td>
    <td class="text-center font-weight-bolder" data-click="openForm" data-id="{{ $schedule->id }}" style="width:10%">
        {{ $title_date }}
        @if ($schedule->start_hour)
            <br>{{ $schedule->start_hour }}
        @else
            <br><i>Cualquier hora</i>
        @endif
    </td>
    <td data-click="openForm" data-id="{{ $schedule->id }}" style="width:15%">

        <div class="row">
            <div class="col-12" style="display: flex;">
                <div style="display: inline-block;">
                    <i class="icon-opacity success font-large-1 mr-1 {{ $schedule->service->avatar ?? 'default' }}"></i>
                </div>
                <div style="display: inline-flex; vertical-align: middle;">
                    <span
                        style="position: relative; top: -5px;">{{ isset($schedule->service->name) ? $schedule->service->name . ' ' . $schedule->num : 'Sin servicio' }}</span>
                </div>
            </div>
        </div>
        <div class="col-12">
            @foreach ($schedule->controls as $control)
                @if ($control->infestation)
                    <span class="badge badge-info">{{ $control->infestation->name }}</span>
                @else
                    <span class="badge badge-danger">SIN CONTROL</span><br>
                @endif
            @endforeach
        </div>
    </td>
    <td data-click="openForm" data-id="{{ $schedule->id }}" style="width:10%;">
        @if (!empty($schedule->raw_technical))
            @foreach ($schedule->raw_technical as $technical_id)
                {{ $users[$technical_id] ?? "N/D" }}<br>
            @endforeach
        @else
            <i>Cualquier técnico</i>
        @endif
    </td>
    <td data-click="openForm" data-id="{{ $schedule->id }}" style="width:25%;">
        <a href="{{ route('crm.gotoSchedule', [$schedule->crm_id, $schedule->office_id]) }}?date={{ $schedule->date }}#tabSchedule"
            target="_blank">
            <div class="row">
                <div class="col-12" style="display: flex;">
                    <div style="display: inline-block;">
                        <img class="avatar xxs mr-1" src="{{ url($schedule->crm->avatar ?? '') }}"
                            onerror="this.src='{{ asset('images/default/customer.png') }}'" />
                    </div>
                    <div style="display: inline-flex; vertical-align: middle;">
                        <span style="position: relative; top: -5px;">
                            {{ strtoupper($schedule->crm->name ?? '') }}<br>
                            {{ $schedule->office->name ?? '' }}, {{ $schedule->office->full_address ?? '' }}
                            <hr>
                            @if ($schedule->office->schedulecontact)
                                {{ $schedule->office->schedulecontact->name }}<br>
                                Tel/cel: @if ($schedule->office->schedulecontact)
                                    {{ $schedule->office->schedulecontact->phone }}
                                @else
                                    <i style="color: #ccc"> Teléfono sin registrar</i>
                                @endif
                            @else
                                <i style="color: #ccc"> Contacto sin registrar</i>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </td>

    <td class="text-center" data-click="openForm" data-id="{{ $schedule->id }}" style="width:15%;">
        @if ($schedule->crmTrackings)
            @foreach ($schedule->crmTrackings as $crmTracking)
                <div class="alert alert-warning">
                    @if ($crmTracking->user_id == auth()->user()->id && $crmTracking->created_at->diffInMinutes(\Carbon\Carbon::now()) <= 30)
                        <i class="las la-times-circle delete-tracking-button" data-id="{{ $crmTracking->id }}"
                            title="Eliminar Seguimiento"></i>
                    @elseif(auth()->user()->roles->pluck('slug')->contains('root'))
                        <i class="las la-times-circle delete-tracking-button" data-id="{{ $crmTracking->id }}"
                            title="Eliminar Seguimiento" style="color: red;"></i>
                    @endif
                    <b>[{{ $crmTracking->date }}]:
                        <hr>{{ $crmTracking->title }}
                    </b>. {{ $crmTracking->detail }}
                </div>
            @endforeach
        @endif
    </td>
    <td class="text-center" data-click="openForm" data-id="{{ $schedule->id }}"
        style="width:10%;min-height: 1px;height: 150px; padding:0px;">
        <div
            style="background-color: {{ $schedule->status->color ?? '#fff' }}; height: 100%; padding-top:10px;color: {{ $schedule->status->text_color ?? '#000' }};">
            <p>{{ $schedule->status->name }}</p>
        </div>


    </td>

    <td class="text-center" data-click="openForm" data-id="{{ $schedule->id }}" style="width:15%;">
        {!! $schedule->observations->count() > 0
            ? $schedule->observations->implode('observation', ' // ')
            : '<i style="color: #ccc"> Ninguna</i>' !!}
    </td>

    <td class="text-center" style="width:10%;">
        <div style="display: inline-flex!important;">
            <div class="dropdown">
                <a data-toggle="dropdown" href="#"><i class="las la-ellipsis-v la-2x cursor-pointer"></i></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <li data-click="openFormEvent" data-id="{{ $schedule->id }}"><i
                            class="las la-phone la-2x"></i><span>Registrar nuevo seguimiento</span></li>
                    <li class="divider" role="presentation"></li>
                    <li data-click="openObservationForm" data-id="{{ $schedule->id }}"><i
                            class="las la-2x">&nbsp;//&nbsp;</i> <span>Nota externa</span></li>
                    <li class="divider" role="presentation"></li>
                    <li data-click="openStatusForm" data-id="{{ $schedule->id }}"><i
                            class="las la-angle-right la-2x"></i><span>Cambiar fecha y estado</span></li>
                </ul>

            </div>

        </div>
    </td>
</tr>
