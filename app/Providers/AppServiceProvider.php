<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(120);
        Carbon::setLocale(config('app.locale'));
        Blade::if('canany', function ($abilities) {
            return app(\Illuminate\Contracts\Auth\Access\Gate::class)->any($abilities);
        });

        Blade::include('components.new', 'new');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
