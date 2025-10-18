<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\PaymentMethod;


class PaymentMethodProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $payment_methods = Cache::remember('active_payment_methods', now()->addHours(6), function () {
                return PaymentMethod::select('name', 'img')
                    ->where('status', 1)
                    ->get();
            });

            $view->with('payment_methods', $payment_methods);
        });
    }
}
