<span align="left" style="margin-top: -48px;">Mostrando del {{$documents->firstItem()}}  al {{$documents->lastItem()}} de {{$documents->total()}} resultados</span>
<div align="right" style="margin-top: -30px;">
    {!!$documents->render()!!}
</div>