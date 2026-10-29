@php 
    $now  = \Carbon\Carbon::now();
    $release = \Carbon\Carbon::parse($date);
@endphp

@if(!$now->gt($release->addDays(8)))
    <i class="la la-star text-success new-star" title="nueva funcionalidad" style="cursor: pointer" data-click="showinfo"  data-title="{{isset($title) ? $title : 'Nueva funcionalidad'}}" data-text="{{isset($text) ? $text : 'No hay una descripción disponible para esta nueva función.'}}"></i>
@endif
