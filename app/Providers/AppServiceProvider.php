<?php

namespace App\Providers;

use App\Models\Lang;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Providers\DefineElementsOfLeftMenu');

        session(['applocale' => Lang::where('default', 1)->first()->name]);

        View::share('langs', Lang::all());

        View::share('lang', Lang::where('lang', session('applocale') ?? 'ro')->get());
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
