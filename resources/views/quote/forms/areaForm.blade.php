
<ul class="nav nav-tabs">  
    @php $count = 0; @endphp
    @foreach($service->areaCustomers as $area)
        @include('quote.forms.frames.plan_added_li')
    @php $count ++; @endphp
    @endforeach
        
    <li class="nav-item">
        <a data-click="formNameWorkPlan" data-id={{$service->id}}
            class="nav-link"><i class="ft-plus-circle"></i>Agregar</a>
    </li>
</ul>
<div class="tab-content px-1 pt-3 underTabContent">
    @php $count = 0; @endphp
    @foreach($service->areaCustomers as $area)
        @include('quote.forms.frames.plan_added_div')
        @php $count++; @endphp
    @endforeach
</div>


