<?php use Carbon\Carbon; ?>
<section>
    <div class="row">
        <!-- BEGIN NAV TICKET -->
        <div class="col-3">
            <div class="grid support">
                <div class="grid-body">
                    {{-- <h2>Filtrar</h2>
                    
                    <hr>
                    
                    <ul>
                        <li class="active"><a href="#">Todos los tickets<span class="pull-right">{{$officeTicket->count()}}</span></a></li>
                        <li><a href="#">Creados por ti<span class="pull-right">13</span></a></li> 
                    </ul>
                    
                    <hr>
                     --}}
                     <h2>Categorias</h2>
                    {{-- <p><strong>Clasificación</strong></p> --}}
                    <ul class="support-label">
                        @foreach ($categories as $category)
                            @php
                                $count=$officeTicket->where('category_id',$category->id)->count();
                            @endphp
                            <li data-diff="2" data-classs="{{$category->class}}" data-filter="{{ $category->slug }}" data-click="categoryFilter" ><a ><span class="{{$category->class}} ">&nbsp;</span>&nbsp;&nbsp;&nbsp;{{$category->name}}<span class="pull-right">{{$count ?? 0}}</span></a></li>
                        @endforeach
                    
                        </ul>
                </div>
            </div>
        </div>
        <!-- END NAV TICKET -->
        <!-- BEGIN TICKET -->
        <div class="col-9">
            <div class="grid support-content" >
                 <div class="grid-body" >
                    <div class="row">
                        <div class="col-6">
                            <h2>Tickets</h2>
                        </div>    
                        <div class="col-6">
                            @can('ticket.create')
                            <button type="button" class="btn btn-success pull-right" data-id="{{null}}" data-click="newEditTicket">Nuevo Ticket</button>    
                            @endcan
                        </div>    
                    </div>    
                     <hr>
                     
                     <div class="btn-group">
                        <button type="button" data-diff="1" data-click="categoryFilter" data-solved="{{ null }}" class="btn nav-link active">{{$officeTicket->count()}} Todos</button>
                        <button type="button" data-diff="1" data-click="categoryFilter" data-solved="{{ 2 }}" class="btn ">{{$officeTicket->where('solved',null)->count()}} Abiertos</button>
                        <button type="button" data-diff="1" data-click="categoryFilter" data-solved="{{ 1 }}" class="btn ">{{$officeTicket->where('solved','!=',null)->count()}} Cerrados</button>
                    </div>
                     
                     <div class="btn-group inline">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> Ordenar: <span class="nav-link active" style="display: inline;" id="selected">Mas recientes<span class="caret"></span></span></button>
                        <ul class="dropdown-menu fa-padding" role="menu">
                            <li data-click="categoryFilter" data-diff='3' data-order="created_at desc">Mas recientes</li>
                            <li data-click="categoryFilter" data-diff='3' data-order="created_at asc">Menos recientes</li>
                            <li data-click="categoryFilter" data-diff='3' data-order="updated_at desc">Actualizados recientemente</li>
                            <li data-click="categoryFilter" data-diff='3' data-order="updated_at asc">Menos actualizados</li>
                            <li data-click="categoryFilter" data-diff='3' data-order="count_answers desc">Mas respondidos</li>
                            <li data-click="categoryFilter" data-diff='3' data-order="count_answers asc">Menos respondidos</li>
                        </ul>
                    </div>
                    
                    
                    <div class="padding"></div>
                    
                    <div class="row" >
                        <!-- BEGIN TICKET CONTENT -->
                        <div class="col-12" >
                            <div id="listTicket">
                            </div>
                    </div>
                        <!-- END TICKET CONTENT -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END TICKET -->
    </div>
</section>