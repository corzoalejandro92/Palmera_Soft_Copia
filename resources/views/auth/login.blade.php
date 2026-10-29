@extends('layouts.login')
@section('title', 'Login')
@section('content')

<section class="flexbox-container">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="col-lg-10 col-md-10 col-10 box-shadow-2 p-0">
            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                <div class="card-group mb-0">
                    <div class="card py-5" style="width:50%">
                        <img style="height: 104%;position: absolute;top: -14px;left: -14px;" src="<?=asset("template/app-assets/images/logo/logo-car.png")?>" alt="">
                    </div>
                <div class="card py-5">
                <div class="card-header border-0">
                    <div class="text-center mb-1">
                        <img style="width: 200px;" src="<?=asset("template/app-assets/images/logo/logo.png")?>" alt="branding logo">
                    </div>
                    <div class="font-large-1  text-center">
                        Acceso al sistema.
                    </div>
                </div>
                <div class="card-content">

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Correo') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Recordarme') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-2">
                                <button type="submit" class="btn round btn-block btn-glow btn-bg-gradient-x-green-green col-12 mr-1 mb-1"> {{ __('Ingresar') }}</button>
                                @if (Route::has('password.request'))
                                <div class="" align="center">
                                    <a class="btn btn-outline-teal round btn-block" href="{{ route('password.request') }}" class="card-link"> {{ __('¿Olvidó su contraseña?') }}</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="card-subtitle text-muted text-left font-small-3 mx-2 my-1"><span><a href="http://softwarewebsas.com" target="_blank" class="card-link">Desarrollado por Software Web S.A.S</a></span></p>
                    </div>
                    <div class="col-6">
                        <p class="card-subtitle text-muted text-right font-small-3 mx-2 my-1"><span><a href="http://palmerajunior.com" target="_blank"  class="card-link">Palmera Junior S.A.S <b class="text-medium-1">&middot;</b> An Anticimex Company</a></span></p>
                    </div>
                </div>
                
                   
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</section>

@endsection
