@extends('layouts.main')
@section('title', 'Crear vehículo')


@section('content')

<form method="POST" action="{{ route('vehicleStore')}}">
    <input type="hidden" name="_token" value="{{ csrf_token()}}">
    <section id="icon-tabs">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Formulario</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @include('vehicle.forms.vehicleForm')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</form>

@endsection