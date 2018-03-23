<?php

namespace App\Providers;

use App\Models\Lang;
use App\Models\Module;
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
        session(['applocale' => Lang::where('default', 1)->first()->name]);

        View::share('langs', Lang::all());

        View::share('lang', Lang::where('lang', session('applocale') ?? Lang::first()->lang)->first());

        View::share('menu', Module::with(['translation'])->get());
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
