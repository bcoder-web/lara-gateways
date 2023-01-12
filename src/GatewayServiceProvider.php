<?php

namespace Betacoders\Gateway;

use Betacoders\Gateway\interfaces\GatewayServiceInterface;
use Betacoders\Gateway\services\paypal\Paypal;
use Betacoders\Gateway\services\stripe\Stripe;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    public function boot(){
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'gateway');
    }

    public function register()
    {
        if (isset(request()->payment_gateway)){
            $this->app->singleton(GatewayServiceInterface::class, function ($app) {
                if (request()->payment_gateway == 'stripe'){
                    return new Stripe();
                }
                return $app;
            });
        }

    }

}
