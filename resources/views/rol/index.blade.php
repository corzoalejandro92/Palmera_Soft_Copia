@extends('layouts.main')
@section('title', 'Lista Roles')
@section('viewcss','user/form')
@section('datatables', 'true')

@section('content')


    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Permisos de los Roles</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('rol/sub.list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection