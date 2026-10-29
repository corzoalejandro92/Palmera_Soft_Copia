@extends('layouts.error')
@section('title', 'Error')
@section('viewcss','errors/error')
@section('content')

 
<div class="text">500</div>
<div class="container">
  <!-- caveman left -->
  <div class="caveman">
    <div class="leg">
      <div class="foot"><div class="fingers"></div></div>      
    </div>
    <div class="leg">
      <div class="foot"><div class="fingers"></div></div>      
    </div>
    <div class="shape">
      <div class="circle"></div>
      <div class="circle"></div>
    </div>
    <div class="head">
      <div class="eye"><div class="nose"></div></div>
      <div class="mouth"></div>
    </div>
    <div class="arm-right"><div class="club"></div></div>    
  </div>
  <!-- caveman right -->
  <div class="caveman">
    <div class="leg">
      <div class="foot"><div class="fingers"></div></div>      
    </div>
    <div class="leg">
      <div class="foot"><div class="fingers"></div></div>      
    </div>
    <div class="shape">
      <div class="circle"></div>
      <div class="circle"></div>
    </div>
    <div class="head">
      <div class="eye"><div class="nose"></div></div>
      <div class="mouth"></div>
    </div>
    <div class="arm-right"><div class="club"></div></div>    
  </div>
</div>
<div class="div-message">
    <h4>Error interno en el servidor.</h4>
    <code>
      {{$messageCatched}}
    </code>
</div>
@endsection