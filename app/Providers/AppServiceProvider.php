<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Core\Auth\PasswordHasher::class,
            \App\Core\Auth\Implementation\BcryptPasswordHasher::class
        );

        $this->app->singleton(
            \App\Core\User\UserRepository::class,
            \App\Core\User\Implementation\EloquentUserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
