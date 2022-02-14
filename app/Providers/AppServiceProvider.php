<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if (request()->is('dashboard*') || request()->is('login')) {
            \Inertia\Inertia::setRootView('layouts.dashboard');
        } else if (request()->is('auth*')) {
            \Inertia\Inertia::setRootView('layouts.auth');
        } else {
            \Inertia\Inertia::setRootView('layouts.app');
        }

        Schema::defaultStringLength(191);
    }
}
