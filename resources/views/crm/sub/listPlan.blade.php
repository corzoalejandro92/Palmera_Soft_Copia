<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs">  
            @php $count = 0; @endphp
            @foreach($areas as $area)
                @include('crm.forms.frames.plan_added_li')
            @php $count ++; @endphp
            @endforeach
            @can('crms.info.admin')    
                <li class="nav-item">
                    <a data-click="addNewArea" data-id={{$crm}}
                        class="nav-link"><i class="ft-plus-circle"></i>Agregar un área</a>
                </li>
            @endcan
        </ul>
        <div class="tab-content px-1 pt-3 underTabContent">
            @php $count = 0; @endphp
            @forelse($areas as $area)
                @include('crm.forms.frames.plan_added_div')
                @php $count++; @endphp
            @empty
            <div class="alert alert-light mb-2 empty-areas" role="alert" align="center">
                <strong>No hay áreas registradas para esta sucursal</strong>
            </div>
            @endforelse
        </div>
    </div>
</div>