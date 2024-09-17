<?php

namespace App\Providers;

use App\Http\Repository\AuthRepository;
use App\Http\Repository\Contracts\AuthRepositoryInterface;
use App\Http\Repository\Contracts\PatientRepositoryinterface;
use App\Http\Repository\Contracts\PostRespositoryinterface;
use App\Http\Repository\PatientRepository;
use App\Http\Repository\PostRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

             // i have to bind the TestInface with the TestRepository
    $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    $this->app->bind(PatientRepositoryinterface::class, PatientRepository::class);
    $this->app->bind(PostRespositoryinterface::class, PostRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
