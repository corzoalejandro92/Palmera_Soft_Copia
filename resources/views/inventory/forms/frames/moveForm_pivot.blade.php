@if(isset($orderPurchase) && $orderPurchase->detailOrderPurchases)
    @foreach($orderPurchase->detailOrderPurchases as $indx => $detailTranferInventory)
    @php
    $key = $detailTranferInventory->id;
    $order = true;
    @endphp
        @include('inventory.forms.frames.moveForm_new_row',compact(['key','detailTranferInventory']))
    @endforeach
@else

    @php if(empty($key)) $key= uniqid(); $detailTranferInventory; $order = true; @endphp
    @include('inventory.forms.frames.moveForm_new_row',compact(['key','detailTranferInventory']))
@endif