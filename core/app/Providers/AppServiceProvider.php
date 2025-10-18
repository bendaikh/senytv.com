<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Session;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        

        //Redirect an Authenticated User To Dashbaord
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('admin.dashboard');
        });

        //Redirect No auth user to admin panel
        Authenticate::redirectUsing(function () {
            Session::flash("fail","You must login");
            return route('admin.login');
        });
    }
}
