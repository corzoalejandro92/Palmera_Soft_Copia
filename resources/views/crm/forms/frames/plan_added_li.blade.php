<li class="nav-item">
    <a class="nav-link areas {{isset($count) && $count == 0 ? 'active':''}}" id="baseIcon-tab1" data-toggle="tab" aria-controls="tabIcon1" href="#tabArea{{$area->id ?? ''}}"
    aria-expanded="true" onclick="localStorage.setItem('currentAreaTab','#tabArea{{$area->id ?? ''}}')" data-id="{{$area->id}}" data-name="{{$area->name}}"> 
        <span  title="Haz doble clic para editar">{{$area->name }}</span>
        @can('crms.info.admin')
            <i class="icon-close" title="Eliminar" style="position: relative;left: 13px;" href="#" data-click="openFormDeleteAreas" data-id="<?= $area->id ?>"></i>
        @endcan
    </a>
</li>
