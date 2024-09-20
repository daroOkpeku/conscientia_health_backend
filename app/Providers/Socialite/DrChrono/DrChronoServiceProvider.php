<?php

namespace App\Providers\Socialite\DrChrono;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Facades\Socialite;
class DrChronoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(Factory::class)->extend('drchrono', function ($app) {
            $config = $app['config']['services.drchrono'];
            return Socialite::buildProvider(Provider::class, $config);
        });
    }
}
